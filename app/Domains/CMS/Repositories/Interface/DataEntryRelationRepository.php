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
}
