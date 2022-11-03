<?php

declare(strict_types=1);

namespace App\Data\Casters;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

class StringToBooleanCast implements Cast
{
    public function __construct(
        private readonly string $trueValue,
    ) {
    }

    /**
     * @param array<int|string, mixed> $context
     */
    public function cast(DataProperty $property, mixed $value, array $context): mixed
    {
        return $value === $this->trueValue;
    }
}
