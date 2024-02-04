<?php

declare(strict_types=1);

namespace App\Dto;

use Illuminate\Http\UploadedFile;

class CreateAbilityData
{
    public function __construct(
        public readonly string $nameEn,
        public readonly string $nameJp,
        public readonly UploadedFile $image,
    ) {
    }
}
