<?php

declare(strict_types=1);

namespace App\Services;

use Exception;

final class CocktailDbClient
{
    private const API_URL = 'https://www.thecocktaildb.com/api/json/v2/';

    /**
     * @param array<string, string> $params
     * @return array<string, mixed>
     */
    public function get(string $endpoint, array $params = []): array
    {
        $url = self::API_URL . config('cocktail-db.api_key') . '/' . $endpoint . '?' . http_build_query($params);

        $response = file_get_contents($url);

        if ($response === false) {
            throw new Exception('Could not get response from ' . $url);
        }

        $result = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($result)) {
            throw new Exception('Invalid JSON');
        }

        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    public function getCategories(): array
    {
        return $this->get('list.php?c=list');
    }

    /**
     * @return array<string, mixed>
     */
    public function getCocktailsByCategory(string $category): array
    {
        return $this->get('filter.php', ['c' => $category]);
    }

    /**
     * @return array<string, mixed>
     */
    public function getCocktailsStartingWithLetter(string $letter): array
    {
        return $this->get('search.php', ['f' => $letter]);
    }
}
