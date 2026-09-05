<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectToNonWww
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $isSecure = $request->isSecure();
        $needsRedirect = false;

        if ($host === 'www.lookathistory.web.id') {
            $host = 'lookathistory.web.id';
            $needsRedirect = true;
        }

        if (app()->environment('production') && ! $isSecure) {
            $needsRedirect = true;
        }

        if ($needsRedirect) {
            return redirect()->to(
                'https://'.$host.$request->getRequestUri(),
                301,
            );
        }

        return $next($request);
    }
}
