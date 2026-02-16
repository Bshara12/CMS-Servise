<?php

namespace App\Http\Controllers;

use App\Domains\CMS\Actions\Data\CreateDataEntryAction;
use App\Domains\CMS\DTOs\Data\CreateDataEntryDto;
use App\Domains\CMS\Requests\DataEntryRequest;
use App\Domains\CMS\Services\DataEntryService;
use App\Domains\CMS\Services\FileUploadService;
use App\Domains\CMS\Services\Versioning\VersionRestoreService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;

class DataEntryController extends Controller
{

  public function __construct(
    private VersionRestoreService $versionRestoreService,
    private DataEntryService $service,
  ) {}

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

    // $scheduledAt = $request->input('scheduled_at')
    //   ? Carbon::parse($request->input('scheduled_at'))->format('Y-m-d H:i:s')
    //   : null;

    $scheduledAt = null;

    if ($request->input('status') === 'scheduled') {
      $scheduledAt = Carbon::parse(
        $request->input('scheduled_at')
      )->format('Y-m-d H:i:s');
    }

    $dto = new CreateDataEntryDto(
      values: $values,
      seo: $request->input('seo'),
      relations: $request->input('relations'),
      status: $request->input('status', 'draft'),
      scheduled_at: $scheduledAt
    );

    $entry = $action->execute(
      projectId: $request->projectId(),
      dataTypeId: $request->dataTypeId(),
      dto: $dto,
      userId: auth()->id()
    );

    return response()->json($entry, 201);
  }


  public function update(DataEntryRequest $request)
  {
    $dto = CreateDataEntryDto::fromRequest($request);
    $entry = $this->service->update(
      $request,
      dto: $dto,
      userId: auth()->id()
    );

    return response()->json([
      'message' => 'Data updated successfully',
      'entry' => $entry
    ]);
  }

  public function destroy($projectId, $dataTypeId, $entryId)
  {
    $this->service->destroy($entryId, $projectId);
    return response()->json(['message' => 'Data deleted successfully']);
  }

  public function restore(int $versionId)
  {
    $this->versionRestoreService
      ->restore($versionId, auth()->id());

    return response()->json([
      'message' => 'Version restored successfully'
    ]);
  }
}
