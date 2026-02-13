<?php

namespace App\Domains\CMS\DTOs\Data;

use App\Domains\CMS\Services\DataEntryService;

class UpdateDataEntryAction
{
  public function __construct(
    private DataEntryService $service
  ) {}

  public function execute(
    int $projectId,
    int $dataTypeId,
    int $entryId,
    CreateDataEntryDto $dto,
    ?int $userId = null
  ) {
    return $this->service->update(
      $projectId,
      $dataTypeId,
      $entryId,
      $dto,
      $userId
    );
  }
}
