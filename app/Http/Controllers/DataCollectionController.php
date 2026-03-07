<?php

namespace App\Http\Controllers;

use App\Domains\CMS\DTOs\DataCollection\CreateDataCollectionDTO;
use App\Domains\CMS\DTOs\DataCollection\UpdateDataCollectionDTO;
use App\Domains\CMS\Services\DataCollectionService;
use App\Domains\CMS\Requests\CreateDataCollectionRequest;
use App\Domains\CMS\Requests\UpdateDataCollectionRequest;
use App\Models\DataCollection;

class DataCollectionController extends Controller
{

  public function __construct(protected DataCollectionService $service) {}

  public function store(CreateDataCollectionRequest $request)
  {
    $dto = CreateDataCollectionDTO::fromRequest($request);
    $this->service->create($dto);

    return response()->json([
      'message' => 'Collection created successfully',
    ]);
  }

  public function update(UpdateDataCollectionRequest $request,DataCollection $collection)
  {
    $dto = UpdateDataCollectionDTO::fromRequest($request, $collection);
    $this->service->update($dto);

    return response()->json([
      'message' => 'Collection updated successfully',
    ]);
  }
}
