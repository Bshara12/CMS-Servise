<?php

namespace App\Http\Controllers;

use App\Domains\CMS\DTOs\Field\CreateFieldDTO;
use App\Domains\CMS\Services\FieldService;
use App\Domains\CMS\Requests\CreateFieldRequest;
use App\Models\DataType;
use App\Models\DataTypeField;

class FieldController extends Controller
{

  protected $service;
  public function __construct(FieldService $service)
  {
    $this->service = $service;
  }

  public function store(CreateFieldRequest $request, DataType $dataType)
  {
    $dto = CreateFieldDTO::fromRequest($request, $dataType);
    $field = $this->service->create($dto);

    return response()->json([
      'message' => 'Field created successfully',
      'data' => $field
    ], 201);
  }

  public function update(CreateFieldRequest $request, DataTypeField $field)
  {
    $dto = CreateFieldDTO::fromRequestForUpdate($request, $field);
    $field = $this->service->update($field, $dto);

    return response()->json([
      'message' => 'Field updated successfully',
      'data'    => $field,
    ], 200);
  }
}
