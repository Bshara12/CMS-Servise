<?php
namespace App\Domains\CMS\Repositories\Eloquent;

use App\Models\DataEntry;
use App\Domains\CMS\Repositories\DataEntryRepositoryInterface;

class EloquentDataEntryRepository implements DataEntryRepositoryInterface
{
    public function create(array $data): DataEntry
    {
        return DataEntry::create($data);
    }

    public function findById(int $id): ?DataEntry
    {
        return DataEntry::find($id);
    }
}