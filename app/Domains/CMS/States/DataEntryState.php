<?php

namespace App\Domains\CMS\States;

use App\Models\DataEntry;

interface DataEntryState
{
    public function publish(DataEntry $entry): void;
}
