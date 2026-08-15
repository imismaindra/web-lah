<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PenggunaController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles')->latest()->get();

        return view('admin.pengguna.index', compact('users'));
    }

    public function approve(User $user): RedirectResponse
    {
        $user->update(['is_approved' => true]);

        return back()->with('success', 'Akun '.$user->name.' berhasil disetujui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return back()->with('success', 'Akun '.$user->name.' berhasil dihapus.');
    }
}
