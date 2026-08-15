<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penulis;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

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
            'name' => 'required_without:user_id|string|max:255',
            'email' => 'required_without:user_id|email|max:255|unique:users,email',
            'password' => 'required_without:user_id|string|min:8|confirmed',
            'nama' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
            'website' => 'nullable|url|max:255',
        ]);

        if (! empty($validated['user_id'])) {
            $user = User::findOrFail($validated['user_id']);
        } else {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
        }

        Role::firstOrCreate(['name' => 'penulis']);
        $user->assignRole('penulis');

        $penulisData = [
            'user_id' => $user->id,
            'nama' => $validated['nama'],
            'bio' => $validated['bio'] ?? null,
            'website' => $validated['website'] ?? null,
        ];

        if ($request->hasFile('avatar')) {
            $penulisData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->penulis()->create($penulisData);

        return redirect()->route('admin.penulis.index')->with('success', 'Penulis berhasil ditambahkan.');
    }

    public function edit(Penulis $penulis): View
    {
        $users = User::whereNotIn('id', Penulis::where('id', '!=', $penulis->id)->pluck('user_id'))
            ->orderBy('name')
            ->get();

        return view('admin.penulis.edit', compact('penulis', 'users'));
    }

    public function update(Request $request, Penulis $penulis): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
            'website' => 'nullable|url|max:255',
        ]);

        $penulisData = [
            'user_id' => $validated['user_id'],
            'nama' => $validated['nama'],
            'bio' => $validated['bio'] ?? null,
            'website' => $validated['website'] ?? null,
        ];

        if ($request->hasFile('avatar')) {
            if ($penulis->avatar) {
                Storage::disk('public')->delete($penulis->avatar);
            }
            $penulisData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if ($validated['user_id'] !== $penulis->user_id) {
            Role::firstOrCreate(['name' => 'penulis']);
            User::findOrFail($validated['user_id'])->assignRole('penulis');
        }

        $penulis->update($penulisData);

        return redirect()->route('admin.penulis.index')->with('success', 'Penulis berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->removeRole('penulis');

        return redirect()->route('admin.penulis.index')->with('success', 'Penulis berhasil dihapus.');
    }
}
