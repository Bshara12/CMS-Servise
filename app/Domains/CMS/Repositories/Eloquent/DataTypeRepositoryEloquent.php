<?php

namespace App\Domains\CMS\Repositories\Eloquent;

use App\Domains\CMS\DTOs\DataType\CreateDataTypeDTO;
use App\Domains\CMS\DTOs\DataType\UpdateDataTypeDTO;
use App\Domains\CMS\Repositories\Interface\DataTypeRepositoryInterface;
use App\Models\DataType;

class DataTypeRepositoryEloquent implements DataTypeRepositoryInterface
{
  public function create(CreateDataTypeDTO $dto): DataType
  {
    return DataType::create([
      'project_id'   => $dto->project_id,
      'name'         => $dto->name,
      'slug'         => $dto->slug,
      'description'  => $dto->description,
      'is_active'    => $dto->is_active ?? true,
      'settings'     => $dto->settings ?? []
    ]);
  }

  public function ensureSlugIsUnique(int $projectId, string $slug): void
  {
    $exists = DataType::where('project_id', $projectId)
      ->where('slug', $slug)
      ->exists();

    if ($exists) {
      abort(422, "Slug '{$slug}' already exists for this project.");
    }
  }

  public function findBySlug(string $slug, int $projectId): ?DataType
  {
    return DataType::where('project_id', $projectId)
      ->where('slug', $slug)
      ->first();
  }

  public function list(int $projectId)
  {
    return DataType::where('project_id', $projectId)
      ->orderBy('name')
      ->get();
  }

  public function ensureSlugIsUniqueForUpdate(int $projectId, string $slug, int $ignoreId): void
  {
    $exists = DataType::where('project_id', $projectId)
      ->where('slug', $slug)
      ->where('id', '!=', $ignoreId)
      ->exists();

    if ($exists) {
      abort(422, "Slug '{$slug}' already exists for this project.");
    }
  }

  public function update(DataType $dataType, UpdateDataTypeDTO $dto): DataType
  {
    $dataType->update([
      'name'        => $dto->name,
      'slug'        => $dto->slug,
      'description' => $dto->description,
      'is_active'   => $dto->is_active,
      'settings'    => $dto->settings,
    ]);

    return $dataType;
  }

  public function delete(DataType $dataType): void
  {
    $dataType->delete();
  }

  public function restore(int $dataTypeId): void
  {
    $dataType = DataType::onlyTrashed()->findOrFail($dataTypeId);
    $dataType->restore();
  }

  public function forceDelete(int $dataTypeId): void
  {
    $dataType = DataType::findOrFail($dataTypeId);
    $dataType->forceDelete();
  }

  public function trashed(int $projectId)
  {
    return DataType::onlyTrashed()->where('project_id', $projectId)->get();
  }
}



// class EntryRepositoryEloquent
// {
//     public function create(CreateEntryDTO $dto): DataEntry
//     {
//         return DataEntry::create([
//             'data_type_id' => $dto->data_type_id,
//             'project_id'   => $dto->project_id,
//             'status'       => $dto->status,
//             'created_by'   => $dto->created_by,
//         ]);
//     }

//     public function storeValues(DataEntry $entry, array $values): void
//     {
//         $dataType = DataType::with('fields')->findOrFail($entry->data_type_id);

//         $fieldsByName = $dataType->fields->keyBy('name');

//         foreach ($values as $fieldName => $value) {
//             /** @var DataTypeField|null $field */
//             $field = $fieldsByName->get($fieldName);

//             if (!$field) {
//                 continue;
//             }

//             if ($field->type === 'relation') {
//                 // العلاقات تُخزن في data_entry_relations (سيتم التعامل معها في Action)
//                 continue;
//             }

//             DataEntryValue::create([
//                 'data_entry_id'     => $entry->id,
//                 'data_type_field_id'=> $field->id,
//                 'language'          => null,
//                 'value'             => is_array($value) ? json_encode($value) : $value,
//             ]);
//         }
//     }

//     public function storeRelations(DataEntry $entry, array $values): void
//     {
//         $dataType = DataType::with('fields')->findOrFail($entry->data_type_id);
//         $fieldsByName = $dataType->fields->keyBy('name');

//         foreach ($values as $fieldName => $value) {
//             /** @var DataTypeField|null $field */
//             $field = $fieldsByName->get($fieldName);

//             if (!$field || $field->type !== 'relation') {
//                 continue;
//             }

//             $settings = $field->settings ?? [];

//             if (!isset($settings['data_type_relation_id'])) {
//                 continue;
//             }

//             $relationId = $settings['data_type_relation_id'];
//             $multiple   = $settings['multiple'] ?? false;

//             $ids = $multiple ? (array) $value : [$value];

//             foreach ($ids as $relatedEntryId) {
//                 if (!$relatedEntryId) {
//                     continue;
//                 }

//                 DataEntryRelation::create([
//                     'data_entry_id'        => $entry->id,
//                     'related_entry_id'     => $relatedEntryId,
//                     'data_type_relation_id'=> $relationId,
//                 ]);
//             }
//         }
//     }
// }
