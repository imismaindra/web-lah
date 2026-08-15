<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    public function notice(): View
    {
        return view('verification-notice');
    }

    public function verify(Request $request, string $id, string $hash): RedirectResponse
    {
        $user = User::findOrFail($id);

        if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
            abort(403);
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->route('login')->with('status', 'Email berhasil diverifikasi. Silakan masuk.');
    }

    public function resend(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route(Auth::user()->hasRole(['admin', 'penulis']) ? 'admin.dashboard' : 'profil.edit');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('status', 'Tautan verifikasi baru telah dikirim ke email Anda.');
    }
}
