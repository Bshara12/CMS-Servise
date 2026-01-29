<?php
namespace App\Domains\CMS\Actions;

use App\Domains\CMS\DTOs\CreateDataTypeDTO;
use App\Domains\CMS\Repositories\DataTypeRepositoryInterface;

class CreateDataTypeAction
{
    public function __construct(
        protected DataTypeRepositoryInterface $repository
    ) {}

    public function execute(CreateDataTypeDTO $dto)
    {
        return $this->repository->create([
            'project_id' => $dto->projectId,
            'name'       => $dto->name,
            'slug'       => $dto->slug,
        ]);
    }
}