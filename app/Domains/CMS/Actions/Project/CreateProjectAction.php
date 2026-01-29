<?php

namespace App\Domains\CMS\Actions\Project;

use App\Domains\CMS\DTOs\CreateProjectDTO;
use App\Domains\CMS\Repositories\Interface\ProjectRepositoryInterface;
use App\Models\Project;

class CreateProjectAction
{
  public function __construct(
    private ProjectRepositoryInterface $repository
  ) {}

  public function execute(CreateProjectDTO $dto): Project
  {
    $project = $this->repository->create($dto->toArray());

  //  $project->users()->attach($dto->ownerId, [
  //     'role' => 'owner'
  //   ]);

    return $project;
  }
}
