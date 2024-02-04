<?php

namespace App\Http\Requests\Pokemon;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * @property-read UploadedFile|null $image
 */
class UpdatePokemonRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'shape' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image'],
            'location_id' => ['nullable', 'integer'],
            'ability_ids' => ['nullable', 'array'],
            'ability_ids.*' => ['integer'],
        ];
    }
}
