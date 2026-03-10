<?php

namespace App\Domains\CMS\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\DataCollection;

class UpdateDataCollectionRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'data_type_id' => 'required|exists:data_types,id',
      'name' => 'required|string|max:255',
      'type' => 'required|in:manual,dynamic',
      'slug' => [
        'required',
        'string',
        'max:255',
        Rule::unique('data_collections', 'slug')->ignore($this->getCurrentCollectionId()),
      ],
      'conditions' => 'nullable|array',
      'conditions.*.field' => 'required_with:conditions|string',
      'conditions.*.operator' => 'required_with:conditions|string',
      'conditions.*.value' => 'required_with:conditions',
      'conditions_logic' => 'nullable|in:and,or',
      'description' => 'nullable|string',
      'is_active' => 'boolean',
      'settings' => 'nullable|array',
    ];
  }

  private function getCurrentCollectionId()
  {
    return DataCollection::where('slug', $this->route('collectionSlug'))->value('id');
  }
}
