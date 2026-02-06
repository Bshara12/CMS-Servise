<?php

namespace App\Domains\CMS\Services;

use App\Domains\CMS\Actions\DataType\CreateDataTypeAction;
use App\Domains\CMS\Actions\DataType\IndexDataTypeAction;
use App\Domains\CMS\Actions\DataType\ShowDataTypeAction;
use App\Domains\CMS\Actions\DataType\UpdateDataTypeAction;
use App\Domains\CMS\Actions\DataType\DeleteDataTypeAction;
use App\Domains\CMS\DTOs\DataType\CreateDataTypeDTO;
use App\Domains\CMS\DTOs\DataType\ShowDataTypeDTOProperities;
use App\Domains\CMS\DTOs\DataType\UpdateDataTypeDTO;
use App\Domains\CMS\Repositories\Interface\DataTypeRepositoryInterface;
use App\Models\DataType;

class DataTypeService
{
  public function __construct(
    protected CreateDataTypeAction $createAction,
    protected IndexDataTypeAction $indexAction,
    protected ShowDataTypeAction $showAction,
    protected UpdateDataTypeAction $updateAction,
    protected DeleteDataTypeAction $deleteAction,
    protected DataTypeRepositoryInterface $repository,
  ) {}

  public function create(CreateDataTypeDTO $dto)
  {
    return $this->createAction->execute($dto);
  }

  public function list()
  {
    $project_id = app('currentProject')->id;
    return $this->indexAction->execute($project_id);
  }

  public function findBySlug(ShowDataTypeDTOProperities $dto)
  {
    return $this->showAction->execute($dto);
  }

  public function update(DataType $dataType, UpdateDataTypeDTO $dto)
  {
    return $this->updateAction->execute($dataType, $dto);
  }

  public function delete(DataType $dataType)
  {
    return $this->deleteAction->execute($dataType);
  }
}
