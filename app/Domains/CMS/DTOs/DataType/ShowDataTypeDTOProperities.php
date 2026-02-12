<?php

namespace App\Domains\CMS\DTOs\DataType;

use App\Domains\CMS\Requests\CreateDataTypeRequest;
use Illuminate\Support\Str;

class ShowDataTypeDTOProperities
{
  public function __construct(
    public int $project_id,
    public string $slug,
  ) {}

  public static function fromRequest(string $slug): self
  {
    $project = app('currentProject');

    return new self(
      project_id: $project->id,
      slug: $slug,
    );
  }
}
