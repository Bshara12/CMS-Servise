<?php

namespace App\Domains\CMS\Actions\Data;

use App\Domains\CMS\DTOs\Data\UpdateEntryDTO;
use App\Domains\CMS\Services\DataEntryService;

class UpdateEntryAction
{
    public function __construct(
        private DataEntryService $service
    ) {}

    public function execute(
        int $entryId,
        UpdateEntryDTO $dto,
        ?int $userId,
        int $projectId
    ) {
        return $this->service->update(
            $entryId,
            $dto,
            $userId,
            $projectId
        );
    }
}
