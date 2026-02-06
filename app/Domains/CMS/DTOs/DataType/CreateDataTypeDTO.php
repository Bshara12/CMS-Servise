<?php

namespace App\Domains\CMS\DTOs;

use App\Http\Requests\CreateDataTypeRequest;
use Illuminate\Support\Str;

class CreateDataTypeDTO
{
  public function __construct(
    public int $project_id,
    public string $name,
    public string $slug,
    public ?string $description = null,
  ) {}

  public static function fromRequest(CreateDataTypeRequest $request): self
  {
    $project = app('currentProject');

    return new self(
      project_id: $project->id,
      name: $request->input('name'),
      slug: $request->input('slug') ?? Str::slug($request->input('name')),
      description: $request->input('description')
    );
  }
}
