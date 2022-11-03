<?php

namespace App\Http\Controllers;

use App\Models\Cocktail;
use Illuminate\Database\Eloquent\Collection;

class CocktailController extends Controller
{
    /**
     * @return Collection<int, Cocktail>
     */
    public function index(): Collection
    {
        return Cocktail::with('ingredients')->get();
    }
}
