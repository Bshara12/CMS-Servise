<?php

namespace App\Domains\CMS\Services;

use App\Domains\CMS\Actions\DataCollection\CreateDataCollectionAction;
use App\Domains\CMS\Actions\DataCollection\DeleteDataCollectionAction;
use App\Domains\CMS\Actions\DataCollection\DeleteDataCollectionItemsAction;
use App\Domains\CMS\Actions\DataCollection\GenerateDynamicItemsAction;
use App\Domains\CMS\Actions\DataCollection\InsertCollectionItemsAction;
use App\Domains\CMS\Actions\DataCollection\RemoveCollectionItemsAction;
use App\Domains\CMS\Actions\DataCollection\ReOrderCollectionItemsAction;
use App\Domains\CMS\Actions\DataCollection\UpdateDataCollectionAction;
use App\Domains\CMS\DTOs\DataCollection\CollectionItemsDTO;
use App\Domains\CMS\DTOs\DataCollection\CreateDataCollectionDTO;
use App\Domains\CMS\Read\Actions\DataCollection\IndexDataCollectionAction;
use App\Domains\CMS\Read\Actions\DataCollection\ShowDataCollectionDetailsAction;

class DataCollectionService
{
  public function __construct(
    protected CreateDataCollectionAction $createAction,
    protected GenerateDynamicItemsAction $generateAction,
    protected UpdateDataCollectionAction $updateAction,
    protected DeleteDataCollectionItemsAction $deleteItemsAction,
    protected DeleteDataCollectionAction $deleteAction,
    protected IndexDataCollectionAction $indexAction,
    protected ShowDataCollectionDetailsAction $showDetailsAction,
    protected InsertCollectionItemsAction $insertItemsAction,
    protected RemoveCollectionItemsAction $removeItemsAction,
    protected ReOrderCollectionItemsAction $reOrderItemsAction
  ) {}

  public function list($projectId)
  {
    return $this->indexAction->execute($projectId);
  }

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

  public function delete($collectionSlug)
  {
    $this->deleteAction->execute($collectionSlug);
  }

  public function show(string $projectKey, string $collectionSlug)
  {
    return $this->showDetailsAction->execute($projectKey, $collectionSlug);
  }

  public function addItems(CollectionItemsDTO $dto)
  {
    $this->insertItemsAction->execute($dto);
  }

  public function removeItems(CollectionItemsDTO $dto)
  {
    $this->removeItemsAction->execute($dto);
  }

  public function reOrderItems(CollectionItemsDTO $dto)
  {
    return $this->reOrderItemsAction->execute($dto);
  }
}
