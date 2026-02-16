<?php

namespace App\Domains\CMS\Actions\Project;

use App\Domains\CMS\DTOs\CreateProjectDTO;
use App\Domains\CMS\Repositories\Interface\ProjectRepositoryInterface;
use App\Domains\Core\Actions\Action;
use App\Models\Project;

class CreateProjectAction extends Action
{
  protected function circuitServiceName(): string
  {
    return 'project.create';
  }

  public function __construct(
    private ProjectRepositoryInterface $repository
  ) {}

  public function execute(CreateProjectDTO $dto): Project
  {
    return $this->run(function () use ($dto) {
      $project = $this->repository->create($dto->toArray());

      //  $project->users()->attach($dto->ownerId, [
      //     'role' => 'owner'
      //   ]);

      return $project;
    });
  }
}
