<?php

namespace App\Domains\CMS\Actions\Project;

use App\Domains\CMS\Repositories\Interface\ProjectRepositoryInterface;
use App\Models\Project;

class ShowProjectAction
{
    public function __construct(
        private ProjectRepositoryInterface $repository
    ) {}

    public function execute(Project $project): Project
    {
        return $this->repository->find($project);
    }
}
