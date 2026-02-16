<?php

namespace App\Domains\CMS\Read\Repositories;

use Illuminate\Support\Facades\DB;

class EntryRelationRepository
{
    public function getParentIds(int $entryId): array
    {
        return DB::table('data_entry_relations')
            ->where('data_entry_id', $entryId)
            ->pluck('related_entry_id')
            ->toArray();
    }

    public function getChildIds(int $entryId): array
    {
      return DB::table('data_entry_relations')
            ->where('related_entry_id', $entryId)
            ->pluck('data_entry_id')
            ->toArray();
    }
}
