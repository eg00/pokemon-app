<?php

namespace App\Http\Requests\Location;

use App\Enums\Region;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateLocationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'alpha_dash:ascii'],
            'region' => ['required', Rule::enum(Region::class)],
            'parent_id' => ['nullable', 'exists:locations,id'],
        ];
    }
}
