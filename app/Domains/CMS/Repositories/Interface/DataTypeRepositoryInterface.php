<?php

namespace App\Domains\CMS\Repositories;

use App\Domains\CMS\DTOs\CreateDataTypeDTO;
use App\Models\DataType;

interface DataTypeRepositoryInterface
{
  public function create(CreateDataTypeDTO $dto): DataType;

  public function ensureSlugIsUnique(int $projectId, string $slug): void;

  public function findBySlug(string $slug, int $projectId): ?DataType;

  public function list(int $projectId);
}
