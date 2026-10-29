<?php

namespace App\Services;

use App\Models\Artikel;
use App\Models\Pageview;
use Illuminate\Http\Request;

class AnalyticsTracker
{
    public function recordArtikelView(Artikel $artikel, Request $request): bool
    {
        $userAgent = $request->userAgent();

        if (BotDetector::isBot($userAgent)) {
            return false;
        }

        $isPrefetch = in_array(strtolower((string) $request->header('Purpose')), ['prefetch', 'preview'], true)
            || in_array(strtolower((string) $request->header('Sec-Purpose')), ['prefetch', 'preview'], true)
            || ($request->headers->has('X-Moz') && strtolower((string) $request->header('X-Moz')) === 'prefetch');

        if ($isPrefetch) {
            return false;
        }

        $sessionKey = 'viewed_artikel_'.$artikel->id;
        if ($request->hasSession() && $request->session()->has($sessionKey)) {
            return false;
        }

        if ($request->hasSession()) {
            $request->session()->put($sessionKey, now()->timestamp);
        }

        $artikel->increment('views');

        Pageview::create([
            'artikel_id' => $artikel->id,
            'user_id' => auth()->id(),
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $userAgent, 0, 500),
            'referer' => substr((string) $request->header('referer'), 0, 500),
        ]);

        return true;
    }
}

