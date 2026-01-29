<?php
namespace App\Domains\CMS\DTOs;

use App\Domains\CMS\Requests\CreateProjectRequest;

class CreateProjectDTO
{
    public function __construct(
        public string $name,
        public int $ownerId,
        public ?array $supportedLanguages,
        public ?array $enabledModules,
    ) {}

    public static function fromRequest(CreateProjectRequest $request): self
    {
        return new self(
            $request->name,
            $request->owner_id,
            $request->supported_languages,
            $request->enabled_modules
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'owner_id' => $this->ownerId,
            'supported_languages' => $this->supportedLanguages,
            'enabled_modules' => $this->enabledModules,
        ];
    }
}
