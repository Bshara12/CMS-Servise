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
}
