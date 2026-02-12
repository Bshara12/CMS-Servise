<?php

namespace App\Domains\CMS\States;

use App\Models\DataEntry;

class DraftState implements DataEntryState
{
    public function publish(DataEntry $entry): void
    {
        $entry->status = 'published';
        $entry->published_at = now();
        $entry->save();
    }
}
