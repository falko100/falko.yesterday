<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

final class Authenticate extends Middleware
{
    /**
     * @param Request $request
     */
    protected function redirectTo($request): string | null
    {
        if ($request->expectsJson()) {
            return null;
        }

        return route('login');
    }
}
