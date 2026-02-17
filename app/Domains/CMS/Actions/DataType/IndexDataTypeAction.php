<?php

namespace App\Domains\CMS\Actions\DataType;

use App\Domains\CMS\Repositories\Interface\DataTypeRepositoryInterface;
use App\Domains\Core\Actions\Action;

class IndexDataTypeAction extends Action
{
  protected function circuitServiceName(): string
  {
    return 'dataType.index';
  }

  public function __construct(
    protected DataTypeRepositoryInterface $repository
  ) {}

  public function execute(int $project_id)
  {
    return $this->run(function () use ($project_id) {
      return $this->repository->list($project_id);
    });
  }
}
