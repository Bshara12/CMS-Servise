<?php

namespace App\Domains\CMS\Actions\Data;

use App\Domains\CMS\DTOs\Data\CreateDataEntryDto;
use App\Domains\CMS\Services\DataEntryService;

class CreateDataEntryAction
{
  public function __construct(
    private DataEntryService $service
  ) {}

  public function execute(
    int $projectId,
    int $dataTypeId,
    CreateDataEntryDto $dto,
    ?int $userId = null
  ) {
    return $this->service->create(
      $projectId,
      $dataTypeId,
      $dto,
      $userId
    );
  }
}
