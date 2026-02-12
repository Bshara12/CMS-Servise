<?php

namespace App\Domains\CMS\Actions\Field;

use App\Domains\CMS\Repositories\Interface\FieldRepositoryInterface;
use App\Models\DataTypeField;
use Illuminate\Support\Facades\DB;

class DeleteFieldAction
{
  public function __construct(
    protected FieldRepositoryInterface $repository,
  ) {}

  public function execute(DataTypeField $field): void
  {
    DB::transaction(function () use ($field) {
      $this->repository->delete($field);
    });
  }
}
