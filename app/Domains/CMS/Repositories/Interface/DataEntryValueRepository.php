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

  /**
   * Filtering helpers used by dynamic collections (Strategy pattern)
   */
  public function pluckEntryIdsByFieldComparison(string $field, string $operator, $value): array;

  public function pluckEntryIdsByFieldLike(string $field, string $pattern): array;

  public function pluckEntryIdsByFieldIn(string $field, array $values): array;

  public function pluckEntryIdsByFieldBetween(string $field, array $values): array;
}
