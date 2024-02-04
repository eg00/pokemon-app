<?php

namespace App\Http\Resources;

use App\Models\Pokemon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

/**
 * @mixin Pokemon
 */
class PokemonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => URL::asset(Storage::url($this->image)),
            'shape' => $this->shape,
            'location' => new LocationResource($this->location),
            'abilities' => AbilityResource::collection($this->abilities),
        ];
    }
}
