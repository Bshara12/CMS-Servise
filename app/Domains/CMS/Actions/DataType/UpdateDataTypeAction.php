<?php

namespace App\Domains\CMS\Actions\DataType;

use App\Domains\CMS\DTOs\DataType\UpdateDataTypeDTO;
use App\Domains\CMS\Repositories\Interface\DataTypeRepositoryInterface;
use App\Models\DataType;
use Illuminate\Support\Facades\DB;

class UpdateDataTypeAction
{
  public function __construct(
    protected DataTypeRepositoryInterface $repository
  ) {}

  public function execute(DataType $dataType, UpdateDataTypeDTO $dto)
  {
    return DB::transaction(function () use ($dataType, $dto) {
      $this->repository->ensureSlugIsUniqueForUpdate(
        projectId: $dataType->project_id,
        slug: $dto->slug,
        ignoreId: $dataType->id
      );

      return $this->repository->update($dataType, $dto);
    });
  }
}
