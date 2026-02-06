<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\CMS\DTOs\DataType\CreateDataTypeDTO;
use App\Domains\CMS\DTOs\DataType\ShowDataTypeDTOProperities;
use App\Domains\CMS\DTOs\DataType\UpdateDataTypeDTO;
use App\Domains\CMS\Services\DataTypeService;
use App\Domains\CMS\Requests\CreateDataTypeRequest;
use App\Domains\CMS\Requests\UpdateDataTypeRequest;
use App\Models\DataType;

class DataTypeController extends Controller
{

  protected $service;

  public function __construct(DataTypeService $service)
  {
    $this->service = $service;
  }

  public function index()
  {
    $types = $this->service->list();
    return response()->json($types);
  }

  public function store(CreateDataTypeRequest $request)
  {
    $dto = CreateDataTypeDTO::fromRequest($request);
    $created = $this->service->create($dto);

    return response()->json([
      'message' => 'DataType created successfully',
      'data' => $created
    ], 201);
  }

  public function show(string $slug)
  {
    $dto = ShowDataTypeDTOProperities::fromRequest($slug);
    $type = $this->service->findBySlug($dto);

    if (!$type) {
      return response()->json(['message' => 'DataType not found'], 404);
    }

    return response()->json($type);
  }

  public function update(
    DataType $dataType,
    UpdateDataTypeRequest $request
  ) {
    $dto = UpdateDataTypeDTO::fromRequest($request);
    $updated = $this->service->update($dataType, $dto);

    return response()->json([
      'message' => 'DataType updated successfully',
      'data' => $updated
    ]);
  }

  public function destroy(DataType $dataType, DataTypeService $service)
  {
    $service->delete($dataType);

    return response()->json([
      'message' => 'DataType deleted successfully'
    ]);
  }
}
