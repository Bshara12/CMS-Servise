<?php

namespace App\Domains\CMS\Services;

use App\Domains\CMS\Actions\DataType\CreateDataTypeAction;
use App\Domains\CMS\DTOs\CreateDataTypeDTO;
use App\Domains\CMS\Repositories\DataTypeRepositoryInterface;

class DataTypeService
{
  public function __construct(
    protected CreateDataTypeAction $createAction,
    protected DataTypeRepositoryInterface $repository,
  ) {}

  public function create(CreateDataTypeDTO $dto)
  {
    return $this->createAction->execute($dto);
  }

  public function list()
  {
    $project = app('currentProject');
    return $this->repository->list($project->id);
  }

  public function findBySlug(string $slug)
  {
    $project = app('currentProject');
    return $this->repository->findBySlug($slug, $project->id);
  }
}
