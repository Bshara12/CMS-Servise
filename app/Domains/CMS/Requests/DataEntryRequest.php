<?php

namespace App\Domains\CMS\Requests;

use App\Domains\CMS\DTOs\Data\CreateDataEntryDTO;
use Illuminate\Foundation\Http\FormRequest;

class DataEntryRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'values' => ['required', 'array'],
      'seo' => ['nullable', 'array'],
      'relations' => ['nullable', 'array'],
      'relations.*.relation_id' => ['required_with:relations', 'integer'],
      'relations.*.related_entry_ids' => ['required_with:relations', 'array'],
      'relations.*.related_entry_ids.*' => ['integer'],
      'files' => ['nullable', 'array'],
      'status' => ['nullable', 'string', 'in:draft,published,scheduled'],
      'scheduled_at' => [
        'required_if:status,scheduled',
        'nullable',
        'date'
      ],
    ];
  }


  public function toDto(): CreateDataEntryDTO
  {
    return new CreateDataEntryDto(
      values: $this->input('values'),
      seo: $this->input('seo'),
      relations: $this->input('relations'),
      status: $this->input('status'),
      scheduled_at: $this->input('scheduled_at')
    );
  }

  public function projectId(): int
  {
    return (int) $this->route('project');
  }

  public function dataTypeId(): int
  {
    return (int) $this->route('dataType');
  }
  public function filesInput(): array
  {
    return $this->file('files', []);
  }

  public function entryId(): int
  {
    return (int) $this->route('entry');
  }
}
