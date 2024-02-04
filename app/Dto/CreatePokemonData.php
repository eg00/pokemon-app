<?php

declare(strict_types=1);

namespace App\Dto;

use Illuminate\Http\UploadedFile;

class CreatePokemonData
{
    /**
     * @param  array<int>  $ability_ids
     */
    public function __construct(
        public readonly string $name,
        public readonly UploadedFile $image,
        public readonly string $shape,
        public readonly int $location_id,
        public readonly array $ability_ids,
    ) {
    }
}
