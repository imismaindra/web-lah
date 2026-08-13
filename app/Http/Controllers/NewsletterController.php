<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        Subscriber::firstOrCreate(['email' => $validated['email']]);

        return back()->with('success', 'Terima kasih! Kamu berhasil berlangganan.');
    }
}
