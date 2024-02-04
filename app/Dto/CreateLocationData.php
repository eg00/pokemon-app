<?php

declare(strict_types=1);

namespace App\Dto;

class CreateLocationData
{
    public function __construct(
        public readonly string $name,
        public readonly string $region,
        public readonly ?int $parentId,
    ) {
    }
}
