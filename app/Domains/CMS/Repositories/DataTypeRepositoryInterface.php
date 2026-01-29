<?php
namespace App\Domains\CMS\Repositories;

use App\Models\DataType;

interface DataTypeRepositoryInterface
{
    public function create(array $data): DataType;

    public function findById(int $id): ?DataType;

    public function findBySlug(int $projectId, string $slug): ?DataType;
}