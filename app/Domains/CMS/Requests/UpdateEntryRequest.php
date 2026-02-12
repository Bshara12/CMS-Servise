<?php

namespace App\Domains\CMS\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // التفويض الحقيقي لاحقاً
    }

    public function rules(): array
    {
        return [
            'values' => ['required', 'array', 'min:1'],

            // مثال: name, price, description...
            'values.*' => ['required', 'array', 'min:1'],

            // مثال: en, ar
            'values.*.*' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'values.required' => 'Values are required',
            'values.array' => 'Values must be an object',
        ];
    }
}
