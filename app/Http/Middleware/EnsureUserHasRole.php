<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isActive() || ! $user->hasRole($roles)) {
            abort(403, 'Akses tidak diizinkan untuk akun ini.');
        }

        return $next($request);
    }
}
