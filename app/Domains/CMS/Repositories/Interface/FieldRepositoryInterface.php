<?php

namespace App\Domains\CMS\Repositories\Interface;

use App\Domains\CMS\DTOs\Field\CreateFieldDTO;
use App\Models\DataTypeField;

interface FieldRepositoryInterface
{
  public function ensureFieldIsUnique(int $data_type_id, string $name): void;

  // public function create(CreateFieldDTO $dto): DataTypeField;

  // public function update(CreateFieldDTO $dto): DataTypeField;

  public function getByDataType(int $dataTypeId);
}
