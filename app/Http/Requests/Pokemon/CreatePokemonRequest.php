<?php

namespace App\Http\Requests\Pokemon;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * @property-read UploadedFile $image
 */
class CreatePokemonRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'shape' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image'],
            'location_id' => ['required', 'integer'],
            'ability_ids' => ['required', 'array'],
            'ability_ids.*' => ['integer'],
        ];
    }
}
