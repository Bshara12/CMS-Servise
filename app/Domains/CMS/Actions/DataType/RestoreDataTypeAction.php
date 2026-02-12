<?php

namespace App\Domains\CMS\Actions\DataType;

use App\Domains\CMS\Repositories\Interface\DataTypeRepositoryInterface;
use App\Models\DataType;
use Illuminate\Support\Facades\DB;

class RestoreDataTypeAction
{
  public function __construct(
    protected DataTypeRepositoryInterface $repository
  ) {}

  public function execute(int $dataTypeId): void
  {
    DB::transaction(function () use ($dataTypeId) {
      $this->repository->restore($dataTypeId);
    });
  }
}
