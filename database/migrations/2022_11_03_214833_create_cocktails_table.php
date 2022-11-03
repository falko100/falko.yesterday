<?php

declare(strict_types=1);

use App\Models\CocktailCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cocktails', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CocktailCategory::class);
            $table->unsignedInteger('cocktail_db_id')->nullable();

            $table->string('name');
            $table->string('image');
            $table->string('video')->nullable();
            $table->boolean('is_alcoholic');
            $table->string('iba')->nullable();
            $table->string('glass');
            $table->text('instructions');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cocktails');
    }
};
