<?php

namespace App\Domains\CMS\Actions\data;

use App\Domains\CMS\Repositories\Interface\DataEntryValueRepository;

class DeleteValuesAction
{
  public function __construct(
    private DataEntryValueRepository $values
  ) {}

  public function execute(int $entryId): void
  {
    $this->values->deleteForEntry($entryId);
  }
}
