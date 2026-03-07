<?php

namespace App\Domains\CMS\DTOs\DataCollection;

use App\Domains\CMS\Requests\UpdateDataCollectionRequest;
use App\Models\DataCollection;

class UpdateDataCollectionDTO
{
  public function __construct(
    public int $collection_id,
    public int $data_type_id,
    public string $name,
    public string $slug,
    public string $type,
    public ?array $conditions,
    public ?string $conditions_logic,
    public ?string $description,
    public bool $is_active,
    public ?array $settings,
  ) {}

  public static function fromRequest(UpdateDataCollectionRequest $request, DataCollection $collection): self
  {
    return new self(
      collection_id: $collection->id,
      data_type_id: $request->data_type_id,
      name: $request->name,
      slug: $request->slug,
      type: $request->type,
      conditions: $request->conditions,
      conditions_logic: $request->conditions_logic ?? 'and',
      description: $request->description,
      is_active: $request->is_active ?? true,
      settings: $request->settings,
    );
  }

  public function toArray(): array
  {
    return [
      'data_type_id' => $this->data_type_id,
      'name' => $this->name,
      'slug' => $this->slug,
      'conditions' => $this->conditions,
      'conditions_logic' => $this->conditions_logic,
      'description' => $this->description,
      'is_active' => $this->is_active,
      'settings' => $this->settings,
    ];
  }
}
