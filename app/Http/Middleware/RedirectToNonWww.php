<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectToNonWww
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->getHost() === 'www.lookathistory.web.id') {
            return redirect()->to(
                'https://lookathistory.web.id'.$request->getRequestUri(),
                301,
            );
        }

        return $next($request);
    }
}
