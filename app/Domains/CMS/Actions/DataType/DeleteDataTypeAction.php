<?php

namespace App\Domains\CMS\Actions\DataType;

use App\Domains\CMS\Repositories\Interface\DataTypeRepositoryInterface;
use App\Models\DataType;
use Illuminate\Support\Facades\DB;

class DeleteDataTypeAction
{
  public function __construct(
    protected DataTypeRepositoryInterface $repository
  ) {}

  public function execute(DataType $dataType): void
  {
    DB::transaction(function () use ($dataType) {
      $this->repository->delete($dataType);
    });
  }
}
