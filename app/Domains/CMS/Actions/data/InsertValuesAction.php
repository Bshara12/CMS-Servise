<?php

namespace App\Domains\CMS\Actions\data;

use App\Domains\CMS\Repositories\Interface\DataEntryValueRepository;

class InsertValuesAction
{
  public function __construct(
    private DataEntryValueRepository $values
  ) {}

  public function execute(int $entryId, int $dataTypeId, array $values): void
  {
    $this->values->bulkInsert($entryId, $dataTypeId, $values);
  }
}
