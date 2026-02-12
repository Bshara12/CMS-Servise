<?php

namespace App\Domains\CMS\Actions\Field;

use App\Domains\CMS\Repositories\Interface\FieldRepositoryInterface;
use App\Models\DataType;

class IndexTrashedFields
{
  public function __construct(
    protected FieldRepositoryInterface $repository
  ) {}

  public function execute(DataType $dataType)
  {
    return $this->repository->indexTrashed($dataType);
  }
}
