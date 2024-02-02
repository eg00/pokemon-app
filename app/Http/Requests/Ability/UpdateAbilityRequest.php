<?php

namespace App\Http\Requests\Ability;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * @property-read UploadedFile|null $image
 */
class UpdateAbilityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name_en' => ['nullable', 'string', 'max:255'],
            'name_jp' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image'],
        ];
    }
}
