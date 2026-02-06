<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Domains\CMS\DTOs\CreateDataTypeDTO;
use App\Domains\CMS\Services\DataTypeService;
use App\Http\Requests\CreateDataTypeRequest;

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

    return response()->json($created, 201);
  }

  public function show(string $slug)
  {
    $type = $this->service->findBySlug($slug);

    if (!$type) {
      return response()->json(['message' => 'DataType not found'], 404);
    }

    return response()->json($type);
  }
}
