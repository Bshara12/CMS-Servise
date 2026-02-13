<?php

namespace App\Domains\CMS\Repositories\Interface;

interface DataEntryRelationRepository
{
  public function insertForEntry(
    int $entryId,
    int $dataTypeId,
    int $projectId,
    array $relations
  ): void;

  public function deleteForEntry(int $entryId): void;

  public function deleteWhereRelatedIs(int $relatedId): void;

  public function getEntriesWhereRelatedIs(int $entryId): array;
}
