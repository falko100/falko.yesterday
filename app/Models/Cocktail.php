<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Cocktail extends Model
{
    protected $fillable = [
        'cocktail_db_id',
        'name',
        'description',
        'image',
        'video',
        'is_alcoholic',
        'iba',
        'glass',
        'instructions',
        'cocktail_category_id',
    ];

    protected $casts = [
        'is_alcoholic' => 'boolean',
    ];

    /**
     * @return BelongsTo<CocktailCategory, Cocktail>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CocktailCategory::class, 'cocktail_category_id');
    }

    /**
     * @return BelongsToMany<Ingredient>
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class)
            ->using(CocktailIngredient::class)
            ->withPivot('measurement');
    }
}
