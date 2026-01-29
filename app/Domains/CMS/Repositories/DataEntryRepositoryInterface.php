<?php
namespace App\Domains\CMS\Repositories;

use App\Models\DataEntry;

interface DataEntryRepositoryInterface
{
    public function create(array $data): DataEntry;

    public function findById(int $id): ?DataEntry;
}