<?php

namespace App\Domains\CMS\Repositories\Interface;

use App\Domains\CMS\DTOs\Field\CreateFieldDTO;
use App\Models\DataType;
use App\Models\DataTypeField;

interface FieldRepositoryInterface
{
  public function ensureFieldIsUnique(int $data_type_id, string $name): void;

  // public function create(CreateFieldDTO $dto): DataTypeField;

  // public function update(CreateFieldDTO $dto): DataTypeField;

  public function getByDataType(int $dataTypeId);
  public function ensureUpdatedFieldIsUnique(int $data_type_id, string $name, int $field_id): void;

  public function create(CreateFieldDTO $dto, array $normalizedSettings): DataTypeField;

  public function update(CreateFieldDTO $dto, DataTypeField $field, array $normalizedSettings): DataTypeField;

  public function list(DataType $dataType);

  public function delete(DataTypeField $field);

  public function restore(int $fieldId): void;

  public function indexTrashed(DataType $dataType);

  public function forceDelete(int $fieldId): void;
}
