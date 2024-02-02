<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enums\Region;

class CreateLocationData
{
    public function __construct(
        public readonly string $name,
        public readonly Region $region,
        public readonly ?int $parentId,
    ) {
    }
}
