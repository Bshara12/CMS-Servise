<?php
namespace App\Domains\CMS\DTOs;

class CreateDataTypeDTO
{
    public function __construct(
        public int $projectId,
        public string $name,
        public string $slug,
        public array $fields = [],
    ) {}
}