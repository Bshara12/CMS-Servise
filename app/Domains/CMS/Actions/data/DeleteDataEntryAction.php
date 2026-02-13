<?php

namespace App\Domains\CMS\Actions\Data;

use App\Domains\CMS\Repositories\Interface\DataEntryRepositoryInterface;
use App\Domains\CMS\Repositories\Interface\DataEntryValueRepository;
use App\Domains\CMS\Repositories\Interface\DataEntryRelationRepository;
use App\Domains\CMS\Repositories\Interface\SeoEntryRepository;
use Illuminate\Support\Facades\DB;

class DeleteDataEntryAction
{
  public function __construct(
    private DataEntryRepositoryInterface $entries,
    private DataEntryValueRepository $values,
    private DataEntryRelationRepository $relations,
    private SeoEntryRepository $seo
  ) {}

  public function execute(int $entryId, int $projectId): void
  {
    DB::transaction(function () use ($entryId, $projectId) {

      $entry = $this->entries->findForProjectOrFail($entryId, $projectId);

      $children = $this->relations->getEntriesWhereRelatedIs($entryId);

      foreach ($children as $child) {
        $this->execute($child['data_entry_id'], $projectId);
      }

      $this->values->deleteForEntry($entryId);

      $this->relations->deleteForEntry($entryId);
      $this->relations->deleteWhereRelatedIs($entryId);

      $this->seo->deleteForEntry($entryId);

      $entry->forceDelete();
    });
  }
}
