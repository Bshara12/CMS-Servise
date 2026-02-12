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

  public function index(DataType $dataType)
  {
    return response()->json($this->service->list($dataType));
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

  public function destroy(DataTypeField $field)
  {
    $this->service->destroy($field);

    return response()->json([
      'message' => 'Data-Type Field deleted successfully'
    ]);
  }

  public function restore($id)
  {
    $this->service->restore($id);

    return response()->json([
      'message' => "Data-Type Field restored successfully"
    ]);
  }

  public function trashed(DataType $dataType)
  {
    $trashed = $this->service->trashed($dataType);

    if ($trashed->isEmpty()) {
      return response()->json([
        'message' => 'No trashed DataTypes found'
      ], 404);
    }
    return response()->json($trashed);
  }

  public function forceDelete($fieldId)
  {
    $this->service->forceDelete($fieldId);

    return response()->json([
      'message' => 'Data-Type Field force deleted successfully'
    ]);
  }
}
