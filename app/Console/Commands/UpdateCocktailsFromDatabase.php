<?php

namespace App\Console\Commands;

use App\Data\DatabaseCocktailData;
use App\Models\Cocktail;
use App\Models\CocktailCategory;
use App\Models\Ingredient;
use App\Services\CocktailDbClient;
use Illuminate\Console\Command;

final class UpdateCocktailsFromDatabase extends Command
{
    /**
     * @var string
     */
    protected $signature = 'cocktail-db:update';

    /**
     * @var string
     */
    protected $description = 'Update cocktails from Cocktail Database';

    /** @var array<string, CocktailCategory> */
    private array $cocktailCategories = [];

    /** @var array<string, Ingredient> */
    private array $ingredients = [];

    public function handle(
        CocktailDbClient $cocktailDbClient,
    ): int {
        $this->info('Updating cocktails from Cocktail Database...');

        $letters = collect(array_merge(range('a', 'z'), range('0', '9')))
            ->map(fn (string|int $letter) => (string)$letter);

        $total = 0;
        $letters->each(function (string $letter) use ($total, $cocktailDbClient) {
            $total += $this->updateCocktailsStartingWithLetter($cocktailDbClient, $letter);
        });

        $this->info("Updated {$total} cocktails from Cocktail Database.");
        return Command::SUCCESS;
    }

    private function updateCocktailsStartingWithLetter(CocktailDbClient $cocktailDbClient, string $letter): int
    {
        $this->info("Updating cocktails starting with letter {$letter}...");
        $apiCocktails = $cocktailDbClient->getCocktailsStartingWithLetter($letter)['drinks'];

        if ($apiCocktails === null || !is_array($apiCocktails)) {
            $this->info("No cocktails starting with letter {$letter} found.");
            return 0;
        }

        $cocktailDataSet = DatabaseCocktailData::collection($apiCocktails);

        $bar = $this->output->createProgressBar(count($cocktailDataSet));
        $bar->start();
        foreach ($cocktailDataSet as $cocktailData) {
            $category = $this->getCategory($cocktailData->category);

            /** @var Cocktail $cocktail */
            $cocktail = $category->cocktails()->firstOrCreate([
                'cocktail_db_id' => $cocktailData->id,
            ], [
                'name' => $cocktailData->name,
                'instructions' => $cocktailData->instructions,
                'image' => $cocktailData->image,
                'video' => $cocktailData->video,
                'is_alcoholic' => $cocktailData->isAlcoholic,
                'iba' => $cocktailData->iba,
                'glass' => $cocktailData->glass,
            ]);

            $this->attachIngredients($cocktail, $cocktailData);

            $bar->advance();
        }
        $bar->finish();
        $this->info('');

        return count($cocktailDataSet);
    }

    private function getCategory(string $category): CocktailCategory
    {
        if (isset($this->cocktailCategories[$category])) {
            return $this->cocktailCategories[$category];
        }

        /** @var CocktailCategory $categoryModel */
        $categoryModel = CocktailCategory::query()->firstOrCreate([
            'name' => $category,
        ]);

        return $this->cocktailCategories[$category] = $categoryModel;
    }

    private function attachIngredients(Cocktail $cocktail, DatabaseCocktailData $cocktailData): void
    {
        $cocktail->ingredients()->detach();

        foreach ($cocktailData->ingredients as $ingredient) {
            $cocktail->ingredients()->attach(
                $this->getIngredient($ingredient->name),
                [
                    'measurement' => $ingredient->measure,
                ],
            );
        }
    }

    private function getIngredient(string $name): Ingredient
    {
        if (isset($this->ingredients[$name])) {
            return $this->ingredients[$name];
        }

        /** @var Ingredient $ingredient */
        $ingredient = Ingredient::query()->firstOrCreate([
            'name' => $name,
        ], [
            'is_in_stock' => false,
        ]);

        return $this->ingredients[$name] = $ingredient;
    }
}
