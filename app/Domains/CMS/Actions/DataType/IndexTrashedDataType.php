<?php

namespace App\Domains\CMS\Actions\DataType;

use App\Domains\CMS\Repositories\Interface\DataTypeRepositoryInterface;
use App\Domains\Core\Actions\Action;

class IndexTrashedDataType extends Action
{
  protected function circuitServiceName(): string
  {
    return 'dataType.indexTrashed';
  }

  public function __construct(
    protected DataTypeRepositoryInterface $repository
  ) {}

  public function execute(int $projectId)
  {
    return $this->run(function () use ($projectId) {
      return $this->repository->trashed($projectId);
    });
  }
}
