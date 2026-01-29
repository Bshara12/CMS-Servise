<?php
namespace App\Domains\CMS\Actions;

use App\Domains\CMS\DTOs\CreateDataEntryDTO;
use App\Domains\CMS\Repositories\DataEntryRepositoryInterface;

class CreateDataEntryAction
{
    public function __construct(
        protected DataEntryRepositoryInterface $repository
    ) {}

    public function execute(CreateDataEntryDTO $dto)
    {
        return $this->repository->create([
            'project_id'   => $dto->projectId,
            'data_type_id' => $dto->dataTypeId,
            'status'       => $dto->status,
            'created_by'   => $dto->createdBy,
            'values'       => $dto->values, // لاحقاً تُعالج داخل الـ repository
        ]);
    }
}