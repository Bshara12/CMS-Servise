<?php

namespace App\Domains\CMS\Actions\DataType;

use App\Domains\CMS\Repositories\Interface\DataTypeRepositoryInterface;

class IndexDataTypeAction
{
  public function __construct(
    protected DataTypeRepositoryInterface $repository
  ) {}

  public function execute(int $project_id)
  {
    return $this->repository->list($project_id);
  }
}
