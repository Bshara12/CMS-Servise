<?php

namespace App\Domains\CMS\DTOs\Data;

use App\Domains\CMS\Requests\UpdateEntryRequest;

class UpdateEntryDTO
{
    public function __construct(
        public readonly array $values
    ) {}

    public static function fromRequest(UpdateEntryRequest $request): self
    {
        return new self(
            values: $request->validated('values')
        );
    }
}
