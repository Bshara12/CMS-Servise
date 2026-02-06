<?php

namespace App\Domains\CMS\Actions\DataType;

use App\Domains\CMS\DTOs\CreateDataTypeDTO;
use App\Domains\CMS\Repositories\DataTypeRepository;
use App\Domains\CMS\Repositories\DataTypeRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CreateDataTypeAction
{
  public function __construct(
    protected DataTypeRepositoryInterface $repository
  ) {}

  public function execute(CreateDataTypeDTO $dto)
  {
    return DB::transaction(function () use ($dto) {

      $this->repository->ensureSlugIsUnique(
        projectId: $dto->project_id,
        slug: $dto->slug
      );

      $dataType = $this->repository->create($dto);

      return $dataType;
    });
  }
}
