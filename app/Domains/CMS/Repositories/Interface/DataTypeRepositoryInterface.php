<?php

namespace App\Domains\CMS\Repositories\Interface;

use App\Domains\CMS\DTOs\DataType\CreateDataTypeDTO;
use App\Domains\CMS\DTOs\DataType\UpdateDataTypeDTO;
use App\Models\DataType;

interface DataTypeRepositoryInterface
{
  public function create(CreateDataTypeDTO $dto): DataType;

  public function ensureSlugIsUnique(int $projectId, string $slug): void;

  public function findBySlug(string $slug, int $projectId): ?DataType;

  public function list(int $projectId);

  public function ensureSlugIsUniqueForUpdate(int $projectId, string $slug, int $ignoreId): void;

  public function update(DataType $dataType, UpdateDataTypeDTO $dto): DataType;

  public function delete(DataType $dataType): void;
}
