<?php

namespace App\Domains\CMS\Actions\Field;

use App\Domains\CMS\Actions\Field\CreationStrategy\FieldTypeFactory;
use App\Domains\CMS\DTOs\Field\CreateFieldDTO;
use App\Domains\CMS\Repositories\Eloquent\FieldRepositoryEloquent;
use App\Models\DataTypeField;

class UpdateFieldAction
{
  public function __construct(
    protected FieldRepositoryEloquent $repository,
    protected CreateFieldAction $createFieldAction
  ) {}

  public function execute(DataTypeField $field, CreateFieldDTO $dto): DataTypeField
  {
    if ($field->type !== $dto->type) {
      abort(422, 'Changing field type is not allowed.');
    }

    $this->repository->ensureUpdatedFieldIsUnique($dto->data_type_id, $dto->name, $field->id);

    $strategy = FieldTypeFactory::make($dto->type);

    $strategy->validateRules($dto->validation_rules);

    $normalizedSettings = $strategy->normalizeSettings($dto->settings);

    if ($dto->type === 'relation') {
      $normalizedSettings['data_type_relation_id'] = $this->createFieldAction->ensureDataTypeRelationExists($dto, $normalizedSettings);
    }

    return $this->repository->update($dto, $field, $normalizedSettings);
  }
}
