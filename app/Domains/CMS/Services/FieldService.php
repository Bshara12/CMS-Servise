<?php

namespace App\Domains\CMS\Services;

use App\Domains\CMS\Actions\Field\CreateFieldAction;
use App\Domains\CMS\Actions\Field\UpdateFieldAction;
use App\Domains\CMS\DTOs\Field\CreateFieldDTO;
use App\Models\DataTypeField;

class FieldService
{
  public function __construct(
    protected CreateFieldAction $createAction,
    protected UpdateFieldAction $updateAction,
  ) {}

  public function create(CreateFieldDTO $dto)
  {
    return $this->createAction->execute($dto);
  }

  public function update(DataTypeField $field, CreateFieldDTO $dto)
  {
    return $this->updateAction->execute($field, $dto);
  }
}
