<?php

namespace App\Domains\CMS\Repositories\Eloquent;

use App\Domains\CMS\Repositories\Interface\DataEntryRepositoryInterface;
use App\Models\DataEntry;
use Illuminate\Support\Facades\DB;

class EloquentDataEntryRepository implements DataEntryRepositoryInterface
{
  public function create(array $data)
  {
    return DataEntry::create($data);
  }
  public function find(int $id): ?DataEntry
  {
    return DataEntry::find($id);
  }

  public function findOrFail(int $id): DataEntry
  {
    return DataEntry::findOrFail($id);
  }
  public function findForProjectOrFail(
    int $entryId,
    int $projectId
  ): object {
    return DB::table('data_entries')
      ->where('id', $entryId)
      ->where('project_id', $projectId)
      ->firstOrFail();
  }

  public function updateStatus(int $id, string $status): void
  {
    DB::table('data_entries')
      ->where('id', $id)
      ->update([
        'status' => $status,
        'updated_at' => now(),
      ]);
  }

  public function schedule(int $id, string $publishAt): void
  {
    DB::table('data_entries')->where('id', $id)->update([
      'status' => 'scheduled',
      'publish_at' => $publishAt,
    ]);
  }

  public function touchUpdatedBy(int $id, ?int $userId): void
  {
    DB::table('data_entries')->where('id', $id)->update([
      'updated_by' => $userId,
      'updated_at' => now(),
    ]);
  }
}
