<?php

namespace App\Domains\CMS\Services;

use App\Domains\CMS\Actions\Data\CreateDataEntryAction;
use App\Domains\CMS\Actions\Data\DeleteDataEntryAction;
use App\Domains\CMS\Actions\data\DeleteValuesAction;
use App\Domains\CMS\Actions\data\HandleRelationsAction;
use App\Domains\CMS\Actions\data\HandleSeoAction;
use App\Domains\CMS\Actions\data\InsertValuesAction;
use App\Domains\CMS\Actions\data\MergeFilesAction;
use App\Domains\CMS\Actions\data\NormalizeScheduledAtAction;
use App\Domains\CMS\Actions\data\ResolveStateAction;
use App\Domains\CMS\Actions\data\ValidateFieldsAction;
use App\Domains\CMS\DTOs\Data\CreateDataEntryDto;
use App\Domains\CMS\DTOs\Data\UpdateEntryDTO;
use App\Domains\CMS\Repositories\Interface\DataEntryRelationRepository;
use App\Domains\CMS\Repositories\Interface\DataEntryRepositoryInterface;
use App\Domains\CMS\Repositories\Interface\DataEntryValueRepository;
use App\Domains\CMS\Repositories\Interface\FieldRepositoryInterface;
use App\Domains\CMS\Repositories\Interface\SeoEntryRepository;
use App\Domains\CMS\Requests\DataEntryRequest;
use App\Domains\CMS\States\DataEntryStateResolver;
use App\Domains\CMS\StrategyCheck\FieldValidatorResolver;
use App\Events\EntryChanged;
use DomainException;
use Illuminate\Support\Facades\DB;

class DataEntryService
{
  public function __construct(
    private DataEntryRepositoryInterface $entries,
    private DataEntryValueRepository $values,
    private SeoEntryRepository $seo,
    private SeoGeneratorService $seoGenerator,
    private DataEntryStateResolver $stateResolver,
    private DataEntryRelationRepository $relations,
    private FieldRepositoryInterface $fieldsRepo,
    private FieldValidatorResolver $validatorResolver,
    private MergeFilesAction $mergeFiles,
    private NormalizeScheduledAtAction $normalizeScheduledAt,
    private ValidateFieldsAction $validateFields,
    private HandleSeoAction $handleSeo,
    private InsertValuesAction $insertValues,
    private DeleteValuesAction $deleteValues,
    private HandleRelationsAction $handleRelations,
    private ResolveStateAction $resolveState,
    private DeleteDataEntryAction $deleteEntry,
    private CreateDataEntryAction $createAction,

  ) {}


  public function create(
    int $projectId,
    int $dataTypeId,
    CreateDataEntryDto $dto,
    ?int $userId
  ) {
    return $this->createAction->execute(
      $projectId,
      $dataTypeId,
      $dto,
      $userId
    );
  }
  public function update(DataEntryRequest $request, CreateDataEntryDto $dto, ?int $userId)
  {
    return DB::transaction(function () use ($request, $dto, $userId) {
      $entryId = $request->entryId();
      $projectId = $request->projectId();
      $dataTypeId = $request->dataTypeId();

      $entry = $this->entries->findForProjectOrFail($entryId, $projectId);

      if ($entry->status === "published") {
        throw new DomainException("Cannot update published entry.");
      }

      $dto->values = $this->mergeFiles->execute($dto->values, $request->filesInput(), $projectId, $dataTypeId);

      $dto->scheduled_at = $this->normalizeScheduledAt->execute($dto->scheduled_at, $dto->status);

      $this->validateFields->execute($dataTypeId, $dto->values);

      $this->resolveState->execute($entry, $dto->status, $dto->scheduled_at);

      $this->deleteValues->execute($entryId);

      $this->insertValues->execute($entryId, $dataTypeId, $dto->values);

      $this->handleSeo->execute($entryId, $dto->seo, $dto->values);

      $this->handleRelations->execute($entryId, $dataTypeId, $projectId, $dto->relations);

      $entry->load('values');

      event(new EntryChanged($entry, $userId));

      return $entry;
    });
  }

  public function destroy(int $entryId, int $projectId)
  {
    $this->deleteEntry->execute($entryId, $projectId);
  }
}
