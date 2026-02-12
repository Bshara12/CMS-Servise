<?php

namespace App\Domains\CMS\Repositories\Interface;

interface DataEntryValueRepository
{
  public function bulkInsert(
    int $entryId,
    int $dataTypeId,
    array $values
  ): void;
  public function getForEntry(int $entryId): array;
  public function deleteForEntry(int $entryId): void;

  public function bulkInsertFromSnapshot(int $entryId, array $values): void;
}
