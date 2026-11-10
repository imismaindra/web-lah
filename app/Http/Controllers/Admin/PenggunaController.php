<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class PenggunaController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with('roles')->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', fn ($q) => $q->where('name', $request->role));
        }

        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        $users = $query->paginate(15)->withQueryString();
        $roles = Role::all();

        return view('admin.pengguna.index', compact('users', 'roles'));
    }

    public function approve(User $user): RedirectResponse
    {
        $user->update(['is_approved' => true]);

        return back()->with('success', 'Akun '.$user->name.' berhasil disetujui.');
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat mengubah peran akun sendiri.');
        }

        $validated = $request->validate([
            'role' => 'nullable|string|in:admin,penulis,pembaca',
        ]);

        if (empty($validated['role']) || $validated['role'] === 'pembaca') {
            $user->syncRoles([]);
        } else {
            Role::firstOrCreate(['name' => $validated['role']]);
            $user->syncRoles([$validated['role']]);
        }

        return back()->with('success', 'Peran akun '.$user->name.' berhasil diperbarui.');
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
