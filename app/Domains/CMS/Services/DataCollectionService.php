<?php

namespace App\Domains\CMS\Services;

use App\Domains\CMS\Actions\DataCollection\CreateDataCollectionAction;
use App\Domains\CMS\Actions\DataCollection\DeleteDataCollectionItemsAction;
use App\Domains\CMS\Actions\DataCollection\GenerateDynamicItemsAction;
use App\Domains\CMS\Actions\DataCollection\UpdateDataCollectionAction;
use App\Domains\CMS\DTOs\DataCollection\CreateDataCollectionDTO;

class DataCollectionService
{
  public function __construct(
    protected CreateDataCollectionAction $createAction,
    protected GenerateDynamicItemsAction $generateAction,
    protected UpdateDataCollectionAction $updateAction,
    protected DeleteDataCollectionItemsAction $deleteItemsAction
  ) {}
  public function create(CreateDataCollectionDTO $dto)
  {
    $collection = $this->createAction->execute($dto);

    if ($dto->type === 'dynamic') {
      $this->generateAction->execute($collection);
    }
  }

  public function update($dto)
  {
    $collection = $this->updateAction->execute($dto);

    if ($dto->type === 'dynamic') {
      $this->deleteItemsAction->execute($dto->collection_id);
      $this->generateAction->execute($collection);
    }
  }
}
