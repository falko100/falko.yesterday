<?php

namespace App\Data;

use App\Data\Casters\StringToBooleanCast;
use App\Data\Pipelines\IngredientDataPipe;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\DataPipeline;
use Spatie\LaravelData\DataPipes\AuthorizedDataPipe;
use Spatie\LaravelData\DataPipes\CastPropertiesDataPipe;
use Spatie\LaravelData\DataPipes\DefaultValuesDataPipe;
use Spatie\LaravelData\DataPipes\MapPropertiesDataPipe;
use Spatie\LaravelData\DataPipes\ValidatePropertiesDataPipe;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Symfony\Contracts\Service\Attribute\Required;

#[MapOutputName(SnakeCaseMapper::class)]
final class DatabaseCocktailData extends Data
{
    /**
     * @param DataCollection<int, DatabaseIngredientData> $ingredients
     */
    public function __construct(
        #[Required]
        #[MapInputName('idDrink')]
        public readonly int $id,

        #[Required]
        #[MapInputName('strDrink')]
        public readonly string $name,

        #[Required]
        #[MapInputName('strDrinkThumb')]
        public readonly string $image,

        #[MapInputName('strVideo')]
        public readonly ?string $video,

        #[Required]
        #[MapInputName('strCategory')]
        public readonly string $category,

        #[Required]
        #[MapInputName('strAlcoholic')]
        #[WithCast(StringToBooleanCast::class, trueValue: 'Alcoholic')]
        public readonly bool $isAlcoholic,

        #[MapInputName('strIBA')]
        public readonly ?string $iba,

        #[Required]
        #[MapInputName('strGlass')]
        public readonly string $glass,

        #[Required]
        #[MapInputName('strInstructions')]
        public readonly string $instructions,

        #[Required]
        #[DataCollectionOf(DatabaseIngredientData::class)]
        public readonly DataCollection $ingredients,
    ) {
    }

    public static function pipeline(): DataPipeline
    {
        return DataPipeline::create()
            ->into(static::class)
            ->through(AuthorizedDataPipe::class)
            ->through(MapPropertiesDataPipe::class)
            ->through(ValidatePropertiesDataPipe::class)
            ->through(IngredientDataPipe::class)
            ->through(DefaultValuesDataPipe::class)
            ->through(CastPropertiesDataPipe::class);
    }
}
