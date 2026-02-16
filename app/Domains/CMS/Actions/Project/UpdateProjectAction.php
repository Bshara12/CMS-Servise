<?php

namespace   App\Domains\CMS\Actions\Project;

use App\Domains\CMS\DTOs\Project\UpdateProjectDTO;
use App\Domains\CMS\Repositories\Interface\ProjectRepositoryInterface;
use App\Domains\Core\Actions\Action;
use App\Models\Project;

class UpdateProjectAction extends Action
{
  protected function circuitServiceName(): string
  {
    return 'project.update';
  }

  public function __construct(
    private ProjectRepositoryInterface $repository
  ) {}

  public function execute(Project $project, UpdateProjectDTO $dto): Project
  {
    return $this->run(function () use ($dto, $project) {
      return $this->repository->update(
        $project,
        $dto->toArray()
      );
    });
  }
}
