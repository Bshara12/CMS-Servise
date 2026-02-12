<?php

namespace App\Domains\CMS\Services;


use App\Domains\CMS\Actions\Field\CreateFieldAction;
use App\Domains\CMS\Actions\Field\DeleteFieldAction;
use App\Domains\CMS\Actions\Field\ForceDeleteAction;
use App\Domains\CMS\Actions\Field\IndexFieldsAction;
use App\Domains\CMS\Actions\Field\IndexTrashedFields;
use App\Domains\CMS\Actions\Field\RestoreFieldAction;
use App\Domains\CMS\Actions\Field\UpdateFieldAction;
use App\Domains\CMS\DTOs\Field\CreateFieldDTO;
use App\Models\DataType;
use App\Models\DataTypeField;

class FieldService
{
  public function __construct(
    protected CreateFieldAction $createAction,
    protected UpdateFieldAction $updateAction,
    protected IndexFieldsAction $IndexFieldsAction,
    protected DeleteFieldAction $deleteAction,
    protected IndexTrashedFields $indexTrashedAction,
    protected RestoreFieldAction $restoreAction,
    protected ForceDeleteAction $forceDeleteAction,
  ) {}

  public function create(CreateFieldDTO $dto)
  {
    return $this->createAction->execute($dto);
  }

  public function update(DataTypeField $field, CreateFieldDTO $dto)
  {
    return $this->updateAction->execute($field, $dto);
  }

  public function list(DataType $dataType)
  {
    return $this->IndexFieldsAction->execute($dataType);
  }

  public function destroy(DataTypeField $field)
  {
    $this->deleteAction->execute($field);
  }

  public function restore(int $fieldId)
  {
    $this->restoreAction->execute($fieldId);
  }

  public function trashed(DataType $dataType)
  {
    return $this->indexTrashedAction->execute($dataType);
  }

  public function forceDelete(int $fieldId)
  {
    $this->forceDeleteAction->execute($fieldId);
  }
}
