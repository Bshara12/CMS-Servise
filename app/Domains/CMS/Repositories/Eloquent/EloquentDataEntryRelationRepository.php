<?php

namespace App\Domains\CMS\Repositories\Eloquent;

use App\Domains\CMS\Repositories\Interface\DataEntryRelationRepository;
use Illuminate\Support\Facades\DB;

class EloquentDataEntryRelationRepository implements DataEntryRelationRepository
{
  public function insertForEntry(
    int $entryId,
    int $dataTypeId,
    int $projectId,
    array $relations
  ): void {

    $rows = [];
    $now = now();

    foreach ($relations as $relation) {

      $relationId = $relation['relation_id'];

      // ✅ 1️⃣ تحقق أن relation تنتمي لنفس data type
      $relationExists = DB::table('data_type_relations')
        ->where('id', $relationId)
        ->where('data_type_id', $dataTypeId)
        ->exists();

      if (!$relationExists) {
        throw new \Exception("Invalid relation: relation does not belong to this data type.");
      }

      foreach ($relation['related_entry_ids'] as $relatedId) {

        // ✅ 2️⃣ تحقق أن related entry موجود وينتمي لنفس المشروع
        $relatedEntry = DB::table('data_entries')
          ->where('id', $relatedId)
          ->where('project_id', $projectId)
          ->first();

        if (!$relatedEntry) {
          throw new \Exception("Related entry {$relatedId} not found in this project.");
        }

        $rows[] = [
          'data_entry_id' => $entryId,
          'related_entry_id' => $relatedId,
          'data_type_relation_id' => $relationId,
          'created_at' => $now,
          'updated_at' => $now,
        ];
      }
    }

    if (!empty($rows)) {
      DB::table('data_entry_relations')->insert($rows);
    }
  }
}
