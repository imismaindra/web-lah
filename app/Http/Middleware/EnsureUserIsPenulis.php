<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsPenulis
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user) {
            abort(403);
        }

        if ($user->hasRole('admin')) {
            return $next($request);
        }

        if (! $user->hasRole('penulis') || ! $user->is_approved) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
