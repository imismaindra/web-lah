<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PenulisController extends Controller
{
    public function index(): View
    {
        $penulis = User::whereHas('roles', fn ($q) => $q->where('name', 'penulis'))
            ->withCount('artikel')
            ->latest()
            ->get();

        return view('admin.penulis.index', compact('penulis'));
    }

    public function create(): View
    {
        $users = User::whereDoesntHave('roles', fn ($q) => $q->where('name', 'penulis'))
            ->orderBy('name')
            ->get();

        return view('admin.penulis.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'nullable|string|min:8|confirmed',
            'nama' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
            'website' => 'nullable|url|max:255',
        ]);

        if ($validated['user_id']) {
            $user = User::findOrFail($validated['user_id']);
        } else {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
        }

        $user->assignRole('penulis');

        $penulisData = [
            'user_id' => $user->id,
            'nama' => $validated['nama'],
            'bio' => $validated['bio'],
            'website' => $validated['website'],
        ];

        if ($request->hasFile('avatar')) {
            $penulisData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->penulis()->create($penulisData);

        return redirect()->route('admin.penulis.index')->with('success', 'Penulis berhasil ditambahkan.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->removeRole('penulis');

        return redirect()->route('admin.penulis.index')->with('success', 'Penulis berhasil dihapus.');
    }
}
