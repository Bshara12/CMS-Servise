<?php

namespace App\Domains\CMS\Actions\Project;

use App\Domains\CMS\DTOs\CreateProjectDTO;
use App\Domains\CMS\Repositories\Interface\ProjectRepositoryInterface;
use App\Domains\Core\Actions\Action;
use App\Models\Project;
use Illuminate\Support\Str;

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
    $data = $dto->toArray();

    $data['public_id'] = Str::uuid()->toString();
    // أو Str::random(32)
    $data['slug'] = Str::slug($data['name']);

    return $this->repository->create($data);
  }
}
