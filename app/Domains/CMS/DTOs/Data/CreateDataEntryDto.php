<?php

namespace App\Domains\CMS\DTOs\Data;

class CreateDataEntryDto
{
  public function __construct(
    public array $values,
    public ?array $seo = null,
    public ?array $relations = null,
    public string $status,
    public ?string $scheduled_at = null,

  ) {}
}
