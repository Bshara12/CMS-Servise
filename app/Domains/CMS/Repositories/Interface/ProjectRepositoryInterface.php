<?php

namespace App\Domains\CMS\Repositories\Interface;

use App\Models\Project;

interface ProjectRepositoryInterface
{
  public function create(array $data): Project;
  public function update(Project $project, array $data): Project;
  public function find(Project $project): Project;
  public function all(): \Illuminate\Support\Collection;
}
