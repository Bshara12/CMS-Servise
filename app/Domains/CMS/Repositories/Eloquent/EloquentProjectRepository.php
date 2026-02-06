<?php

namespace App\Domains\CMS\Repositories\Eloquent;

use App\Domains\CMS\Repositories\Interface\ProjectRepositoryInterface;
use App\Models\Project;

class EloquentProjectRepository implements ProjectRepositoryInterface
{
  public function create(array $data): Project
  {
    return Project::create($data);
  }
  public function update(Project $project, array $data): Project
  {
    $project->update($data);
    return $project->refresh();
  }
  public function find(Project $project): Project
  {
    return $project;
  }

  public function all(): \Illuminate\Support\Collection
  {
    return Project::query()->latest()->get();
  }

  public function delete(Project $project): void
  {
    $project->delete();
  }
}
