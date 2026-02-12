<?php

namespace App\Domains\CMS\Actions\DataType;

use App\Domains\CMS\Repositories\Interface\DataTypeRepositoryInterface;

class IndexTrashedDataType
{
  public function __construct(
    protected DataTypeRepositoryInterface $repository
  ) {}

  public function execute(int $projectId)
  {
    return $this->repository->trashed($projectId);
  }
}
