<?php

namespace App\Domains\CMS\Read\Repositories;

use Illuminate\Support\Facades\DB;

// class EntryTypeReadRepository
// {
//     public function getDataTypeId(int $entryId): ?int
//     {
//         return DB::table('data_entries')
//             ->where('id', $entryId)
//             ->value('data_type_id');
//     }

//     public function getPublishedEntriesByType(
//         int $dataTypeId
//     ) {

//         return DB::table('data_entries')
//             ->where('data_type_id', $dataTypeId)
//             // ->where('status', 'published')
//             ->where(function ($q) {
//                 $q->whereNull('scheduled_at')
//                   ->orWhere('scheduled_at', '<=', now());
//             })
//             ->pluck('id')
//             ->toArray();
//     }
// }

class EntryTypeReadRepository
{
  public function getDataTypeId(int $entryId): ?int
  {
    return DB::table('data_entries')
      ->where('id', $entryId)
      ->value('data_type_id');
  }

  public function queryPublishedByType(int $dataTypeId)
  {
    return DB::table('data_entries')
      ->where('data_type_id', $dataTypeId)
      ->where(function ($q) {
        $q->whereNull('scheduled_at')
          ->orWhere('scheduled_at', '<=', now());
      })
      ->whereNull('deleted_at');
  }
}
