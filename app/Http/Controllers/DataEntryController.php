<?php

namespace App\Http\Controllers;

use App\Domains\CMS\Actions\Data\CloneDataEntryAction;
use App\Domains\CMS\Actions\Data\CreateDataEntryAction;
use App\Domains\CMS\Actions\Data\PublishDataEntryAction;
use App\Domains\CMS\Actions\Data\UpdateDraftEntryAction;
use App\Domains\CMS\Actions\Data\UpdateEntryAction;
use App\Domains\CMS\DTOs\Data\CreateDataEntryDto;
use App\Domains\CMS\DTOs\Data\UpdateEntryDTO;
use App\Domains\CMS\Requests\DataEntryRequest;
use App\Domains\CMS\Requests\UpdateEntryRequest;
use App\Domains\CMS\Services\FileUploadService;
use Illuminate\Http\Request;

class DataEntryController extends Controller
{
  //
  public function store(
    DataEntryRequest $request,
    CreateDataEntryAction $action,
    FileUploadService $uploader
  ) {
    $values = $request->input('values', []);
    $files = $request->filesInput();

    foreach ($files as $fieldId => $langs) {
      foreach ($langs as $lang => $uploadedFiles) {
        foreach ((array) $uploadedFiles as $file) {
          $path = $uploader->upload(
            $file,
            $request->projectId(),
            $request->dataTypeId(),
            (int) $fieldId
          );

          $values[$fieldId][$lang][] = $path;
        }
      }
    }

    $entry = $action->execute(
      projectId: $request->projectId(),
      dataTypeId: $request->dataTypeId(),
      dto: new CreateDataEntryDto($values, $request->input('seo')),
      userId: auth()->id()
    );

    return response()->json($entry, 201);
  }

  public function update(
    int $id,
    UpdateEntryRequest $request,
    UpdateEntryAction $action
  ) {
    $projectId = (int) request()->header('X-Project-Id');

    return response()->json(
      $action->execute(
        $id,
        UpdateEntryDTO::fromRequest($request),
        auth()->id(),
        $projectId
      )
    );
  }
}
