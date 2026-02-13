<?php

namespace App\Domains\CMS\Services\Versioning;

use App\Models\DataEntry;
use App\Models\DataEntryVersion;
use Illuminate\Support\Facades\DB;

class VersionRestoreService
{
    public function __construct(
        protected VersionCreator $versionCreator
    ) {}

    public function restore(int $versionId, ?int $userId = null): void
    {
        DB::transaction(function () use ($versionId, $userId) {

            $version = DataEntryVersion::findOrFail($versionId);
            $snapshot = $version->snapshot;

            $entry = DataEntry::findOrFail($version->data_entry_id);

            // حذف القيم الحالية
            $entry->values()->delete();

            // إعادة إدخال snapshot values (bulk insert)
            $bulk = collect($snapshot['values'])->map(function ($value) use ($entry) {
                return [
                    'data_entry_id' => $entry->id,
                    'data_type_field_id' => $value['data_type_field_id'],
                    'language' => $value['language'],
                    'value' => $value['value'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            $entry->values()->insert($bulk);

            // تحديث حالة entry
            $entry->update([
                'status' => $snapshot['entry']['status'],
                'scheduled_at' => $snapshot['entry']['scheduled_at'],
                'published_at' => $snapshot['entry']['published_at'],
            ]);

            // إنشاء version جديدة تمثل عملية restore
            $this->versionCreator->create($entry, $userId);
        });
    }
}
