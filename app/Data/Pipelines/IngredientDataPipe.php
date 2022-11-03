<?php

declare(strict_types=1);

namespace App\Data\Pipelines;

use Exception;
use Illuminate\Support\Collection;
use Spatie\LaravelData\DataPipeline;
use Spatie\LaravelData\Support\DataClass;

final class IngredientDataPipe extends DataPipeline
{
    /**
     * @param array<string|int, mixed> $payload
     * @param Collection<string|int, mixed> $properties
     * @return Collection<string|int, mixed>
     */
    public function handle(array $payload, DataClass $class, Collection $properties): Collection
    {
        $ingredients = [];

        foreach ($payload as $key => $value) {
            if (str_starts_with((string)$key, 'strIngredient') && $value !== null) {
                if (!is_string($value)) {
                    throw new Exception('Ingredient is not a string');
                }

                $ingredient = [];
                $ingredient['name'] = $value;

                $measure = $payload['strMeasure' . substr((string)$key, 13)];

                if (is_string($measure)) {
                    $ingredient['measure'] = $measure;
                } else {
                    $ingredient['measure'] = null;
                }

                $ingredients[] = $ingredient;
            }
        }

        $properties->put('ingredients', $ingredients);

        return $properties->filter(
            fn ($value, $key) => !str_starts_with((string)$key, 'strIngredient')
                && !str_starts_with((string)$key, 'strMeasure')
        );
    }
}
