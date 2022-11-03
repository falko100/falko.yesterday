<?php

namespace App\Data;

use Spatie\LaravelData\Data;

final class DatabaseIngredientData extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $measure,
    ) {
    }
}
