<?php

namespace App\Domains\CMS\Actions\Data;

use App\Domains\CMS\DTOs\Data\CreateDataEntryDto;
use App\Domains\CMS\Services\DataEntryService;
use App\Domains\Core\Actions\Action;

class CreateDataEntryAction extends Action
{
  protected function circuitServiceName(): string
  {
    return 'dataEntry.create';
  }

  public function __construct(
    private DataEntryService $service
  ) {}

  public function execute(
    int $projectId,
    int $dataTypeId,
    CreateDataEntryDto $dto,
    ?int $userId = null
  ) {
    return $this->run(function () use ($dto, $projectId, $dataTypeId, $userId) {
      return $this->service->create(
        $projectId,
        $dataTypeId,
        $dto,
        $userId
      );
    });
  }
}
