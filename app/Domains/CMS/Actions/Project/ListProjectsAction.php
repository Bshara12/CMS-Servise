<?php

namespace App\Domains\CMS\Actions\Project;

use App\Domains\CMS\Repositories\Interface\ProjectRepositoryInterface;

class ListProjectsAction
{
  public function __construct(
    private ProjectRepositoryInterface $repository
  ) {}

  public function execute(): \Illuminate\Support\Collection
  {
    return $this->repository->all();
  }
}
