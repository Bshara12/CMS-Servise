<?php

namespace App\Domains\CMS\Repositories\Eloquent;

use App\Domains\CMS\Repositories\Interface\DataEntryValueRepository;
use Illuminate\Support\Facades\DB;

class EloquentDataEntryValueRepository implements DataEntryValueRepository
{

  public function bulkInsert(
    int $entryId,
    int $dataTypeId,
    array $values
  ): void {
    // 1️⃣ جلب الحقول
    $fields = DB::table('data_type_fields')
      ->where('data_type_id', $dataTypeId)
      ->get()
      ->keyBy('name'); // name = slug تبع الحقل

    $rows = [];
    $now = now();

    foreach ($values as $fieldSlug => $langs) {
      if (!isset($fields[$fieldSlug])) {
        throw new \Exception("Field {$fieldSlug} does not exist in this data type.");
      }


      $fieldId = $fields[$fieldSlug]->id;

      // foreach ($langs as $lang => $value) {
      //   $rows[] = [
      //     'data_entry_id' => $entryId,
      //     'data_type_field_id' => $fieldId,
      //     'language' => $lang,
      //     'value' => (string) $value,
      //     'created_at' => $now,
      //     'updated_at' => $now,
      //   ];
      // }
      foreach ($langs as $lang => $value) {

        // ✅ إذا القيمة array (مثل file field)
        if (is_array($value)) {

          foreach ($value as $singleValue) {

            $rows[] = [
              'data_entry_id' => $entryId,
              'data_type_field_id' => $fieldId,
              'language' => $lang,
              'value' => (string) $singleValue,
              'created_at' => $now,
              'updated_at' => $now,
            ];
          }
        } else {

          // ✅ الحقول العادية (text, number...)
          $rows[] = [
            'data_entry_id' => $entryId,
            'data_type_field_id' => $fieldId,
            'language' => $lang,
            'value' => (string) $value,
            'created_at' => $now,
            'updated_at' => $now,
          ];
        }
      }
    }

    DB::table('data_entry_values')->insert($rows);
  }

  public function getForEntry(int $entryId): array
  {
    return DB::table('data_entry_values')
      ->where('data_entry_id', $entryId)
      ->get()
      ->map(fn($row) => (array) $row)
      ->toArray();
  }
  public function deleteForEntry(int $entryId): void
  {
    DB::table('data_entry_values')
      ->where('data_entry_id', $entryId)
      ->delete();
  }

  public function bulkInsertFromSnapshot(int $entryId, array $values): void
  {
    $rows = [];
    $now = now();

    foreach ($values as $row) {
      $rows[] = [
        'data_entry_id' => $entryId,
        'data_type_field_id' => $row['data_type_field_id'],
        'language' => $row['language'],
        'value' => $row['value'],
        'created_at' => $now,
        'updated_at' => $now,
      ];
    }

    DB::table('data_entry_values')->insert($rows);
  }

  public function pluckEntryIdsByFieldComparison(string $field, string $operator, $value): array
  {
    return DB::table('data_entry_values')
      ->join('data_type_fields', 'data_type_fields.id', '=', 'data_entry_values.data_type_field_id')
      ->where('data_type_fields.name', $field)
      ->where('data_entry_values.value', $operator, $value)
      ->pluck('data_entry_values.data_entry_id')
      ->toArray();
  }

  public function pluckEntryIdsByFieldLike(string $field, string $pattern): array
  {
    return DB::table('data_entry_values')
      ->join('data_type_fields', 'data_type_fields.id', '=', 'data_entry_values.data_type_field_id')
      ->where('data_type_fields.name', $field)
      ->where('data_entry_values.value', 'LIKE', $pattern)
      ->pluck('data_entry_values.data_entry_id')
      ->toArray();
  }

  public function pluckEntryIdsByFieldIn(string $field, array $values): array
  {
    if (empty($values)) {
      return [];
    }

    return DB::table('data_entry_values')
      ->join('data_type_fields', 'data_type_fields.id', '=', 'data_entry_values.data_type_field_id')
      ->where('data_type_fields.name', $field)
      ->whereIn('data_entry_values.value', $values)
      ->pluck('data_entry_values.data_entry_id')
      ->toArray();
  }

  public function pluckEntryIdsByFieldBetween(string $field, array $values): array
  {
    if (count($values) !== 2) {
      return [];
    }

    return DB::table('data_entry_values')
      ->join('data_type_fields', 'data_type_fields.id', '=', 'data_entry_values.data_type_field_id')
      ->where('data_type_fields.name', $field)
      ->whereBetween('data_entry_values.value', [$values[0], $values[1]])
      ->pluck('data_entry_values.data_entry_id')
      ->toArray();
  }
}
