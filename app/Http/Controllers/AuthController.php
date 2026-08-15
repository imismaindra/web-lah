<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private function panelOrProfil(User $user): string
    {
        return $user->hasRole(['admin', 'penulis'])
            ? route('admin.dashboard')
            : route('profil.edit');
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->to($this->panelOrProfil(Auth::user()));
        }

        return view('auth.login');
    }

    public function showPanelLogin()
    {
        if (Auth::check()) {
            return redirect()->to($this->panelOrProfil(Auth::user()));
        }

        return view('auth.panel-login');
    }

    public function login(Request $request)
    {
        return $this->authenticate($request, allowPanel: false);
    }

    public function panelLogin(Request $request)
    {
        return $this->authenticate($request, allowPanel: true);
    }

    private function authenticate(Request $request, bool $allowPanel)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()->withErrors([
                'email' => 'Email atau kata sandi tidak valid.',
            ])->onlyInput('email');
        }

        $user = Auth::user();
        $request->session()->regenerate();

        $isPanel = $user->hasRole(['admin', 'penulis']);

        if ($isPanel && ! $allowPanel) {
            Auth::logout();
            $request->session()->regenerate();

            return redirect()->route('panel.login')
                ->with('status', 'Akun ini adalah admin/penulis. Silakan masuk lewat halaman panel.');
        }

        if (! $isPanel && $allowPanel) {
            Auth::logout();
            $request->session()->regenerate();

            return redirect()->route('login')
                ->with('status', 'Akun ini bukan admin/penulis. Silakan masuk lewat halaman pembaca.');
        }

        if (! $user->hasRole('admin') && ! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        if (! $user->is_approved) {
            Auth::logout();
            $request->session()->regenerate();

            return back()->withErrors([
                'email' => 'Akun Anda belum disetujui oleh admin. Silakan tunggu konfirmasi.',
            ])->onlyInput('email');
        }

        return redirect()->intended($this->panelOrProfil($user));
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route($this->panelOrProfil(Auth::user()));
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_approved' => false,
        ]);

        $user->sendEmailVerificationNotification();

        return redirect()->route('login')->with('status', 'Pendaftaran berhasil. Silakan verifikasi email Anda, lalu tunggu persetujuan admin.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
