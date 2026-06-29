<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class FeatureFlagMiddleware
{
    /** @param Closure(Request): Response $next */
    public function handle(Request $request, Closure $next, string $flag): Response
    {
        if (! config("features.{$flag}")) {
            abort(404);
        }

        return $next($request);
    }
}
