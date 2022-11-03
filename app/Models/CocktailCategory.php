<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class CocktailCategory extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * @return HasMany<Cocktail>
     */
    public function cocktails(): HasMany
    {
        return $this->hasMany(Cocktail::class);
    }
}
