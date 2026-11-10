<?php

namespace App\Http\Controllers;

use App\Models\KontakPesan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class KontakController extends Controller
{
    public function index(): View
    {
        return view('kontak');
    }

    public function store(Request $request): RedirectResponse
    {
        // Honeypot check
        if (filled($request->input('website')) || filled($request->input('phone_confirm'))) {
            return back()->with('status', 'Pesan terkirim. Kami akan membalas secepatnya.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        KontakPesan::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'] ?? null,
            'message' => $validated['message'],
            'ip_address' => $request->ip(),
        ]);

        try {
            Mail::raw(
                "Pesan baru dari halaman kontak.\n\n".
                "Nama: {$validated['name']}\n".
                "Email: {$validated['email']}\n\n".
                $validated['message'],
                function ($message) use ($validated) {
                    $message->to(config('mail.from.address'))
                        ->subject('Pesan baru dari halaman kontak: '.($validated['subject'] ?? $validated['name']));
                }
            );
        } catch (\Throwable $e) {
            report($e);
        }

        return back()->with('status', 'Pesan terkirim. Kami akan membalas secepatnya.');
    }
}
