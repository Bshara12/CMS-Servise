<?php

namespace App\Domains\CMS\Repositories\Eloquent;

use App\Domains\CMS\Repositories\Interface\DataEntryValueRepository;
use Illuminate\Support\Facades\DB;


class EloquentDataEntryValueRepository implements DataEntryValueRepository
{
  // public function bulkInsert(
  //   int $entryId,
  //   int $dataTypeId,
  //   array $values
  // ): void {
  //   $rows = [];

  //   foreach ($values as $fieldId => $localizedValues) {
  //     foreach ($localizedValues as $lang => $value) {
  //       $rows[] = [
  //         'data_entry_id' => $entryId,
  //         'data_type_field_id' => $fieldId,
  //         'language' => $lang,
  //         'value' => is_array($value)
  //           ? json_encode($value)
  //           : $value,
  //         'created_at' => now(),
  //         'updated_at' => now(),
  //       ];
  //     }
  //   }

  //   DB::table('data_entry_values')->insert($rows);
  // }

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
        continue; // أو throw exception
      }

      $fieldId = $fields[$fieldSlug]->id;

      foreach ($langs as $lang => $value) {
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
