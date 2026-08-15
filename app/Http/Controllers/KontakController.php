<?php

namespace App\Http\Controllers;

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
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        Mail::raw(
            "Pesan baru dari halaman kontak.\n\n".
            "Nama: {$validated['name']}\n".
            "Email: {$validated['email']}\n\n".
            $validated['message'],
            function ($message) {
                $message->to(config('mail.from.address'))
                    ->subject('Pesan baru dari halaman kontak');
            }
        );

        return back()->with('status', 'Pesan terkirim. Kami akan membalas secepatnya.');
    }
}
