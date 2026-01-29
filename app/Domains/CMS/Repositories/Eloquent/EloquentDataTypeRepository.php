<?php
namespace App\Domains\CMS\Repositories\Eloquent;

use App\Models\DataType;
use App\Domains\CMS\Repositories\DataTypeRepositoryInterface;

class EloquentDataTypeRepository implements DataTypeRepositoryInterface
{
    public function create(array $data): DataType
    {
        return DataType::create($data);
    }

    public function findById(int $id): ?DataType
    {
        return DataType::find($id);
    }

    public function findBySlug(int $projectId, string $slug): ?DataType
    {
        return DataType::where('project_id', $projectId)
            ->where('slug', $slug)
            ->first();
    }
}