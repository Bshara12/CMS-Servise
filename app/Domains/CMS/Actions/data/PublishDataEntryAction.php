<?php

namespace App\Domains\CMS\Actions\Data;

use App\Domains\CMS\Services\DataEntryService;

class PublishDataEntryAction
{
    public function __construct(
        private DataEntryService $service
    ) {}

    public function execute(int $entryId, ?int $userId)
    {
        return $this->service->publish($entryId, $userId);
    }
}
