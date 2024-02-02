<?php

declare(strict_types=1);

namespace App\Dto;

use Illuminate\Http\UploadedFile;

class CreateAbilityData
{
    public function __construct(
        public readonly string $name_en,
        public readonly string $name_jp,
        public readonly UploadedFile $image,
    ) {
    }
}
