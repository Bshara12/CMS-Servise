<?php

namespace   App\Domains\CMS\Actions\Project;

use App\Domains\CMS\DTOs\Project\UpdateProjectDTO;
use App\Domains\CMS\Repositories\Interface\ProjectRepositoryInterface;
use App\Models\Project;

class UpdateProjectAction
{
  public function __construct(
    private ProjectRepositoryInterface $repository
  ) {}

  public function execute(Project $project, UpdateProjectDTO $dto): Project
  {
    return $this->repository->update(
      $project,
      $dto->toArray()
    );
  }
}
