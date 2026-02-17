<?php

namespace App\Domains\CMS\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
  public function upload(
    UploadedFile $file,
    int $projectId,
    int $dataTypeId,
    int $fieldId
  ): string {
    $path = sprintf(
      'projects/%d/data-types/%d/fields/%d',
      $projectId,
      $dataTypeId,
      $fieldId
    );

    return $file->store($path, 'public');
  }

  // public function publish(int $entryId, ?int $userId)
  // {
  //   return DB::transaction(function () use ($entryId, $userId) {

  //     $entry = $this->entries->findOrFail($entryId);

  //     $state = $this->stateResolver->resolve($entry);
  //     $state->publish($entry);

  //     $snapshot = [
  //       'entry' => $entry->toArray(),
  //       'values' => $this->values->getForEntry($entry->id),
  //       'seo' => $this->seo->getForEntry($entry->id),
  //     ];

  //     $this->versions->create(
  //       $entry->id,
  //       1,
  //       $snapshot,
  //       $userId
  //     );

  //     return $entry;
  //   });
  // }
}
