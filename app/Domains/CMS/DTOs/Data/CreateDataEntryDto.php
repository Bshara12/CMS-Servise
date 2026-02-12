<?php

namespace App\Domains\CMS\DTOs\Data;

class CreateDataEntryDto
{
  public function __construct(
    public array $values,
    public ?array $seo = null
  ) {}
}
