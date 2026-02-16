<?php

namespace App\Domains\CMS\Actions\DataType;

use App\Domains\CMS\DTOs\DataType\ShowDataTypeDTOProperities;
use App\Domains\CMS\Repositories\Interface\DataTypeRepositoryInterface;
use App\Domains\Core\Actions\Action;

class ShowDataTypeAction extends Action
{
  protected function circuitServiceName(): string
  {
    return 'dataType.show';
  }

  public function __construct(
    protected DataTypeRepositoryInterface $repository
  ) {}

  public function execute(ShowDataTypeDTOProperities $dto)
  {
    return $this->run(function () use ($dto) {
      return $this->repository->findBySlug($dto->slug, $dto->project_id);
    });
  }
}
