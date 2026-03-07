<?php

namespace App\Domains\CMS\Actions\DataCollection;

use App\Domains\CMS\Repositories\Interface\DataCollectionRepositoryInterface;
use App\Domains\Core\Actions\Action;

class DeleteDataCollectionItemsAction extends Action
{

  protected function circuitServiceName(): string
  {
    return 'dataCollection.deleteItems';
  }

  public function __construct(protected DataCollectionRepositoryInterface $repository) {}

  public function execute($collection_id)
  {
    return $this->run(function () use ($collection_id) {
      return $this->repository->deleteItems($collection_id);
    });
  }
}
