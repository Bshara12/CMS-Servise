<?php

namespace App\Domains\CMS\Actions\Field;

use App\Domains\CMS\Repositories\Interface\FieldRepositoryInterface;

class ForceDeleteAction
{
  public function __construct(
    protected FieldRepositoryInterface $repository
  ) {}

  public function execute(int $fieldId)
  {
    return $this->repository->forceDelete($fieldId);
  }
}
