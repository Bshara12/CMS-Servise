<?php

namespace App\Domains\CMS\Repositories\Eloquent;

use App\Domains\CMS\DTOs\DataCollection\CreateDataCollectionDTO;
use App\Domains\CMS\DTOs\DataCollection\UpdateDataCollectionDTO;
use App\Domains\CMS\Repositories\Interface\DataCollectionRepositoryInterface;
use App\Models\DataCollection;
use App\Models\DataCollectionItem;

class DataCollectionRepositoryEloquent implements DataCollectionRepositoryInterface
{

  public function create(CreateDataCollectionDTO $dto): DataCollection
  {
    return DataCollection::create($dto->toArray());
  }

  public function createDataCollectionItem(array $data): void
  {
    DataCollectionItem::create($data);
  }

  public function update(UpdateDataCollectionDTO $dto): DataCollection
  {
    $collection = DataCollection::findOrFail($dto->collection_id);
    $collection->update($dto->toArray());
    return $collection;
  }

  public function deleteItems(int $collectionId): void
  {
    DataCollectionItem::where('collection_id', $collectionId)->delete();
  }

  public function find(int $projectId, int $id): ?DataCollection
  {
    return DataCollection::where('project_id', $projectId)->find($id);
  }

  public function list(int $projectId)
  {
    return DataCollection::where('project_id', $projectId)->get();
  }

  public function trashed(int $projectId)
  {
    return DataCollection::onlyTrashed()
      ->where('project_id', $projectId)
      ->get();
  }

  public function restore(int $id): void
  {
    DataCollection::onlyTrashed()->findOrFail($id)->restore();
  }

  public function forceDelete(int $id): void
  {
    DataCollection::withTrashed()->findOrFail($id)->forceDelete();
  }
}
