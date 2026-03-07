<?php

namespace App\Domains\CMS\Repositories\Interface;

use App\Domains\CMS\DTOs\DataCollection\CreateDataCollectionDTO;
use App\Domains\CMS\DTOs\DataCollection\UpdateDataCollectionDTO;
use App\Models\DataCollection;

interface DataCollectionRepositoryInterface
{
  public function create(CreateDataCollectionDTO $dto): DataCollection;

  public function createDataCollectionItem(array $data): void;

  public function update(UpdateDataCollectionDTO $dto): DataCollection;

  public function deleteItems(int $collectionId): void;

  public function find(int $projectId, int $id): ?DataCollection;

  public function list(int $projectId);

  public function trashed(int $projectId);

  public function restore(int $id): void;

  public function forceDelete(int $id): void;
}
