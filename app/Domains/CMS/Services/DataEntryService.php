<?php

namespace App\Domains\CMS\Services;

use App\Domains\CMS\DTOs\Data\CreateDataEntryDto;
use App\Domains\CMS\DTOs\Data\UpdateEntryDTO;
use App\Domains\CMS\Repositories\Interface\DataEntryRelationRepository;
use App\Domains\CMS\Repositories\Interface\DataEntryVersionRepository;
use App\Domains\CMS\Repositories\Interface\DataEntryRepositoryInterface;
use App\Domains\CMS\Repositories\Interface\DataEntryValueRepository;
use App\Domains\CMS\Repositories\Interface\FieldRepositoryInterface;
use App\Domains\CMS\Repositories\Interface\SeoEntryRepository;
use App\Domains\CMS\States\DataEntryStateResolver;
use App\Domains\CMS\StrategyCheck\FieldValidatorResolver;
use DomainException;
use Illuminate\Support\Facades\DB;

class DataEntryService
{
  public function __construct(
    private DataEntryRepositoryInterface $entries,
    private DataEntryValueRepository $values,
    private SeoEntryRepository $seo,
    private SeoGeneratorService $seoGenerator,
    private DataEntryVersionRepository $versions,
    private DataEntryStateResolver $stateResolver,
    private DataEntryRelationRepository $relations,
    private FieldRepositoryInterface $fieldsRepo,
    private FieldValidatorResolver $validatorResolver,


  ) {}

  private function validateFields(
    int $dataTypeId,
    array $values,
    array $files
  ): void {

    $fields = $this->fieldsRepo->getByDataType($dataTypeId);

    foreach ($fields as $slug => $field) {

      if ($field->required && !isset($values[$slug])) {
        throw new DomainException("Field {$slug} is required.");
      }

      if (!isset($values[$slug])) {
        continue;
      }

      foreach ($values[$slug] as $lang => $value) {

        $validator = $this->validatorResolver->resolve($field->type);

        $validator->validate($value, (array) $field);
      }
    }
  }

  public function create(
    int $projectId,
    int $dataTypeId,
    CreateDataEntryDto $dto,
    ?int $userId
  ) {
    return DB::transaction(function () use ($projectId, $dataTypeId, $dto, $userId) {

      $entry = $this->entries->create([
        'project_id' => $projectId,
        'data_type_id' => $dataTypeId,
        'status' => 'draft',
        'scheduled_at' => $dto->scheduled_at ?? null,
        'created_by' => $userId, // nullable
      ]);
      $this->validateFields(
        $dataTypeId,
        $dto->values,
        $dto->files ?? []
      );

      $state = $this->stateResolver->resolve($entry);

      if ($dto->status === 'published') {
        $state->publish($entry);
      }

      if ($dto->status === 'scheduled') {
        $state->schedule($entry, $dto->scheduled_at);
      }

      $this->values->bulkInsert(
        $entry->id,
        $dataTypeId,
        $dto->values
      );

      if ($dto->seo) {
        $this->seo->insertForEntry($entry->id, $dto->seo);
      } else {
        $generatedSeo = $this->seoGenerator->generate($dto->values);
        $this->seo->insertForEntry($entry->id, $generatedSeo);
      }
      if ($dto->relations) {
        $this->relations->insertForEntry(
          $entry->id,
          $dataTypeId,
          $projectId,
          $dto->relations
        );
      }

      if ($dto->scheduled_at) {
        try {
          $dto->scheduled_at = \Carbon\Carbon::parse($dto->scheduled_at)
            ->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
          throw new DomainException("Invalid scheduled_at format.");
        }
      }


      return $entry;
    });
  }





  public function update(
    int $entryId,
    UpdateEntryDTO $dto,
    ?int $userId,
    int $projectId
  ) {
    return DB::transaction(function () use ($entryId, $dto, $userId, $projectId) {

      // 1️⃣ جلب entry ضمن المشروع
      $entry = $this->entries->findForProjectOrFail($entryId, $projectId);

      if ($entry->status === 'archived') {
        throw new DomainException('Archived entry cannot be updated');
      }

      // 2️⃣ draft → تعديل مباشر
      if ($entry->status === 'draft') {
        $this->replaceValues($entry, $dto, $userId);
        return $entry;
      }

      // 3️⃣ published / scheduled → نسخة جديدة
      $newEntry = $this->cloneAsDraft($entry, $userId);

      $this->replaceValues($newEntry, $dto, $userId);

      // 4️⃣ إعادة الحالة تلقائياً
      if ($entry->status === 'published') {
        $this->entries->updateStatus($newEntry->id, 'published');
      }

      if ($entry->status === 'scheduled') {
        $this->entries->schedule(
          $newEntry->id,
          $entry->publish_at
        );
      }

      return $newEntry;
    });
  }

  private function replaceValues(
    object $entry,
    UpdateEntryDTO $dto,
    ?int $userId
  ): void {
    $this->values->deleteForEntry($entry->id);

    $this->values->bulkInsert(
      $entry->id,
      $entry->data_type_id,
      $dto->values
    );

    $this->entries->touchUpdatedBy($entry->id, $userId);
  }

  private function cloneAsDraft(
    object $entry,
    ?int $userId
  ): object {
    $new = $this->entries->create([
      'project_id'   => $entry->project_id,
      'data_type_id' => $entry->data_type_id,
      'status'       => 'draft',
      'created_by'   => $userId,
    ]);

    $values = $this->values->getForEntry($entry->id);

    $this->values->bulkInsertFromSnapshot(
      $new->id,
      $values
    );

    return $new;
  }
}
