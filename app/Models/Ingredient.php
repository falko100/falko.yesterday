<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Ingredient extends Model
{
    protected $fillable = [
        'name',
        'is_in_stock',
    ];

    protected $casts = [
        'is_in_stock' => 'boolean',
    ];
}
