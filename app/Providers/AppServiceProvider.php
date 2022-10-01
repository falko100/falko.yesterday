<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Relation::enforceMorphMap([
            'user' => User::class,
        ]);
    }
}
