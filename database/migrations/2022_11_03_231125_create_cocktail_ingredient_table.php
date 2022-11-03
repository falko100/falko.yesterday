<?php

declare(strict_types=1);

use App\Models\Cocktail;
use App\Models\Ingredient;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cocktail_ingredient', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cocktail::class);
            $table->foreignIdFor(Ingredient::class);
            $table->string('measurement')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cocktail_ingredient');
    }
};
