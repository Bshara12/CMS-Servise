<?php

namespace App\Domains\CMS\Repositories\Interface;

use App\Domains\CMS\DTOs\DataCollection\CreateDataCollectionDTO;
use App\Domains\CMS\DTOs\DataCollection\UpdateDataCollectionDTO;
use App\Models\DataCollection;

interface DataCollectionRepositoryInterface
{
  public function getBySlug(string $slug): ?DataCollection;

  public function create(CreateDataCollectionDTO $dto): DataCollection;

  public function createDataCollectionItem(array $data): void;

  public function update(UpdateDataCollectionDTO $dto): DataCollection;

  public function delete(int $collectionId): void;

  public function deleteItems(int $collectionId): void;

  public function list(int $projectId);

  public function find(int $projectId, string $slug): ?DataCollection;

  public function getCollectionItems(int $collectionId);

  public function insertItems(int $collectionId, array $items): void;

  public function removeItems(int $collectionId, array $items): void;

  public function reOrderItems(int $collectionId, array $items);
}
