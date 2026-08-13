<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Era;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EraController extends Controller
{
    public function index(): View
    {
        $eras = Era::orderBy('urutan')->get();

        return view('admin.era.index', compact('eras'));
    }

    public function create(): View
    {
        return view('admin.era.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'periode' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('era', 'public');
        }

        Era::create($validated);

        return redirect()->route('admin.era.index')->with('success', 'Era berhasil ditambahkan.');
    }

    public function edit(Era $era): View
    {
        return view('admin.era.edit', compact('era'));
    }

    public function update(Request $request, Era $era): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'periode' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            if ($era->gambar) {
                \Storage::disk('public')->delete($era->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('era', 'public');
        }

        $era->update($validated);

        return redirect()->route('admin.era.index')->with('success', 'Era berhasil diperbarui.');
    }

    public function destroy(Era $era): RedirectResponse
    {
        if ($era->gambar) {
            \Storage::disk('public')->delete($era->gambar);
        }

        $era->delete();

        return redirect()->route('admin.era.index')->with('success', 'Era berhasil dihapus.');
    }
}
