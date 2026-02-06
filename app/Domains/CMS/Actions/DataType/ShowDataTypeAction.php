<?php

namespace App\Domains\CMS\Actions\DataType;

use App\Domains\CMS\DTOs\DataType\ShowDataTypeDTOProperities;
use App\Domains\CMS\Repositories\Interface\DataTypeRepositoryInterface;

class ShowDataTypeAction
{
  public function __construct(
    protected DataTypeRepositoryInterface $repository
  ) {}

  public function execute(ShowDataTypeDTOProperities $dto)
  {
    return $this->repository->findBySlug($dto->slug, $dto->project_id);
  }
}
