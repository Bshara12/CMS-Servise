<?php

namespace App\Domains\CMS\Read\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// class EntryReadRepository implements EntryReadRepositoryInterface
// {
//   /**
//    * Fetch a published entry with its values and SEO data.
//    *
//    * - Ensures entry is published and scheduled correctly.
//    * - Fetches values with language → fallback → null fallback logic.
//    * - Fetches SEO with language priority.
//    */

//   public function findPublishedWithValues(
//     int $entryId,
//     string $language,
//     string $fallback
//   ): ?array {

//     // Fetch entry
//     $entry = DB::table('data_entries')
//       ->where('id', $entryId)
//       ->where(function ($q) {
//         $q->whereNull('scheduled_at')
//           ->orWhere('scheduled_at', '<=', now());
//       })
//       ->first();

//     if (!$entry) {
//       return null;
//     }

//     // Fetch values with language + fallback logic
//     $valuesQuery = DB::table('data_entry_values as v')
//       ->select('f.name')
//       ->join('data_type_fields as f', 'f.id', '=', 'v.data_type_field_id')
//       ->where('v.data_entry_id', $entryId);

//     if ($language == 0 || $language === "0") {
//       // لا يوجد لغة → رجّع القيمة الأساسية فقط
//       $values = $valuesQuery
//         ->selectRaw("MAX(CASE WHEN v.language = '0' THEN v.value END) as value")
//         ->groupBy('f.name')
//         ->get()
//         ->pluck('value', 'name')
//         ->toArray();
//     } else {
//       $values = $valuesQuery
//         ->selectRaw("
//                     COALESCE(
//                         MAX(CASE WHEN v.language = ? THEN v.value END),
//                         MAX(CASE WHEN v.language = ? THEN v.value END),
//                         MAX(CASE WHEN v.language = '0' THEN v.value END)
//                     ) as value
//                 ", [$language, $fallback])
//         ->groupBy('f.name')
//         ->get()
//         ->pluck('value', 'name')
//         ->toArray();
//     }

//     // Fetch SEO with language priority
//     $seo = DB::table('seo_entries')
//       ->where('data_entry_id', $entryId)
//       ->where(function ($q) use ($language, $fallback) {
//         $q->where('language', $language)
//           ->orWhere('language', $fallback);
//       })
//       ->orderByRaw("language = '$language' DESC")
//       ->first();

//     return [
//       'id' => $entry->id,
//       'status' => $entry->status,
//       'values' => $values,
//       'seo' => $seo ? (array) $seo : [],
//     ];
//   }

//   /**
//    * Fetch many published entries with their values (bulk).
//    */
//   public function findPublishedManyWithValues(
//     array $entryIds,
//     string $language,
//     string $fallback
//   ): array {
//     if (empty($entryIds)) {
//       return [];
//     }

//     // 1️⃣ fetch entries
//     $entries = DB::table('data_entries')
//       ->whereIn('id', $entryIds)
//       ->where(function ($q) {
//         $q->whereNull('scheduled_at')
//           ->orWhere('scheduled_at', '<=', now());
//       })
//       ->get()
//       ->keyBy('id');


//     if ($entries->isEmpty()) {
//       return [];
//     }

//     // 2️⃣ fetch values (bulk)
//     $values = DB::table('data_entry_values')
//       ->whereIn('data_entry_id', $entries->keys())
//       ->where(function ($q) use ($language, $fallback) {
//         $q->where('language', $language)
//           ->orWhere('language', $fallback)
//           ->orWhere('language', "0"); // للأرقام
//       })
//       ->get()
//       ->groupBy('data_entry_id');

//     // 3️⃣ mapping
//     $result = [];

//     foreach ($entries as $entry) {

//       $entryValues = $values[$entry->id] ?? collect();

//       $mappedValues = [];

//       foreach ($entryValues as $value) {
//         $mappedValues[$value->data_type_field_id] = $value->value;
//       }

//       $result[] = [
//         'id' => $entry->id,
//         'status' => $entry->status,
//         'values' => $mappedValues,
//       ];
//     }

//     return $result;
//   }
// }

class EntryReadRepository implements EntryReadRepositoryInterface
{
  /**
   * Fetch single published entry with values + SEO
   */
  public function findPublishedWithValues(
    int $entryId,
    string $language,
    string $fallback
  ): ?array {

    $entry = DB::table('data_entries')
      ->where('id', $entryId)
      ->where(function ($q) {
        $q->whereNull('scheduled_at')
          ->orWhere('scheduled_at', '<=', now());
      })
      ->first();

    if (!$entry) {
      return null;
    }

    // 🔹 Fetch values with field type
    $valuesQuery = DB::table('data_entry_values as v')
      ->join('data_type_fields as f', 'f.id', '=', 'v.data_type_field_id')
      ->where('v.data_entry_id', $entryId)
      ->whereIn('v.language', [$language, $fallback, "0"])
      ->select(
        'f.name',
        'f.type',
        'v.language',
        'v.value'
      )
      ->get()
      ->groupBy('name');

    $mappedValues = [];

    foreach ($valuesQuery as $fieldName => $fieldGroup) {

      // language priority
      $selected = $fieldGroup->firstWhere('language', $language)
        ?? $fieldGroup->firstWhere('language', $fallback)
        ?? $fieldGroup->firstWhere('language', "0");

      if (!$selected) {
        continue;
      }

      $type = $selected->type;

      // 🔥 لو ميديا
      if (in_array($type, ['image', 'file'])) {

        $mediaItems = [];

        foreach ($fieldGroup as $item) {

          if (!$item->value) continue;

          $path = $item->value;

          $mediaItems[] = [
            'url'       => asset($path),
            'name'      => basename($path),
            'extension' => pathinfo($path, PATHINFO_EXTENSION),
          ];
        }

        $mappedValues[$fieldName] = $mediaItems;
      } else {
        $mappedValues[$fieldName] = $selected->value;
      }
    }

    // 🔹 SEO
    $seo = DB::table('seo_entries')
      ->where('data_entry_id', $entryId)
      ->where(function ($q) use ($language, $fallback) {
        $q->where('language', $language)
          ->orWhere('language', $fallback);
      })
      ->orderByRaw("language = '$language' DESC")
      ->first();

    return [
      'id'     => $entry->id,
      'status' => $entry->status,
      'values' => $mappedValues,
      'seo'    => $seo ? (array) $seo : [],
    ];
  }


  /**
   * Fetch many published entries (bulk optimized)
   */

  public function findPublishedManyWithValues(
    array $entryIds,
    string $language,
    string $fallback
  ): array {

    if (empty($entryIds)) {
      return [];
    }

    // 1️⃣ fetch entries
    $entries = DB::table('data_entries')
      ->whereIn('id', $entryIds)
      ->where(function ($q) {
        $q->whereNull('scheduled_at')
          ->orWhere('scheduled_at', '<=', now());
      })
      ->get()
      ->keyBy('id');

    if ($entries->isEmpty()) {
      return [];
    }

    // 2️⃣ fetch values with field type + is_multiple
    $values = DB::table('data_entry_values as v')
      ->join('data_type_fields as f', 'f.id', '=', 'v.data_type_field_id')
      ->whereIn('v.data_entry_id', $entries->keys())
      ->whereIn('v.language', [$language, $fallback, "0"])
      ->select(
        'v.data_entry_id',
        'f.name',
        'f.type',
        'v.language',
        'v.value'
      )
      ->get()
      ->groupBy('data_entry_id');

    $result = [];

    foreach ($entries as $entry) {

      $entryValues = $values[$entry->id] ?? collect();
      $mapped = [];

      foreach ($entryValues->groupBy('name') as $fieldName => $fieldGroup) {

        $selected = $fieldGroup->firstWhere('language', $language)
          ?? $fieldGroup->firstWhere('language', $fallback)
          ?? $fieldGroup->firstWhere('language', "0");

        if (!$selected) {
          continue;
        }

        $type = $selected->type;

        if (in_array($type, ['image', 'file'])) {

          $mediaItems = [];

          foreach ($fieldGroup as $item) {

            if (!$item->value) continue;

            $path = $item->value;

            $mediaItems[] = [
              'url'       => asset($path),
              'name'      => basename($path),
              'extension' => pathinfo($path, PATHINFO_EXTENSION),
            ];
          }

          if (count($mediaItems) > 1) {
            $mapped[$fieldName] = $mediaItems;
          } else {
            $mapped[$fieldName] = $mediaItems[0] ?? null;
          }
        } else {
          $mapped[$fieldName] = $selected->value;
        }
      }


      $result[] = [
        'id'     => $entry->id,
        'status' => $entry->status,
        'values' => $mapped,
      ];
    }

    return $result;
  }
}
