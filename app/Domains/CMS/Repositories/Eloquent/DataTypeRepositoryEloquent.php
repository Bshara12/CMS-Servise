<?php

namespace App\Domains\CMS\Repositories\Eloquent;

use App\Domains\CMS\DTOs\CreateDataTypeDTO;
use App\Domains\CMS\Repositories\DataTypeRepositoryInterface;
use App\Models\DataType;

class DataTypeRepositoryEloquent implements DataTypeRepositoryInterface
{
  public function create(CreateDataTypeDTO $dto): DataType
  {
    return DataType::create([
      'project_id'   => $dto->project_id,
      'name'         => $dto->name,
      'slug'         => $dto->slug,
      'description'  => $dto->description,
    ]);
  }

  public function ensureSlugIsUnique(int $projectId, string $slug): void
  {
    $exists = DataType::where('project_id', $projectId)
      ->where('slug', $slug)
      ->exists();

    if ($exists) {
      abort(422, "Slug '{$slug}' already exists for this project.");
    }
  }

  public function findBySlug(string $slug, int $projectId): ?DataType
  {
    return DataType::where('project_id', $projectId)
      ->where('slug', $slug)
      ->first();
  }

  public function list(int $projectId)
  {
    return DataType::where('project_id', $projectId)
      ->orderBy('name')
      ->get();
  }
}
