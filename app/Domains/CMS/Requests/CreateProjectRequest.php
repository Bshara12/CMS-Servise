<?php
namespace App\Domains\CMS\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // لاحقًا auth service
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'owner_id' => 'required|exists:users,id',
            'supported_languages' => 'nullable|array',
            'enabled_modules' => 'nullable|array',
        ];
    }
}
