<?php

declare(strict_types=1);

namespace App\Dto;

use Illuminate\Http\UploadedFile;

class UpdatePokemonData
{
    /**
     * @param  array<int>  $abilityIds
     */
    public function __construct(
        public readonly ?string $name,
        public readonly ?UploadedFile $image,
        public readonly ?string $shape,
        public readonly ?int $locationId,
        public readonly array $abilityIds,
    ) {
    }
}
