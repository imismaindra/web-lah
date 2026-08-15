<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && ! $user->hasRole('admin') && ! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
