<?php

namespace App\Domains\CMS\States;

use App\Models\DataEntry;
use DomainException;

class PublishedState implements DataEntryState
{
    public function publish(DataEntry $entry): void
    {
        throw new DomainException('Entry already published');
    }
}
