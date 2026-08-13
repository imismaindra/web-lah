<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topik;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TopikController extends Controller
{
    public function index(): View
    {
        $topiks = Topik::orderBy('urutan')->get();

        return view('admin.topik.index', compact('topiks'));
    }

    public function create(): View
    {
        return view('admin.topik.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'urutan' => 'nullable|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('topik', 'public');
        }

        Topik::create($validated);

        return redirect()->route('admin.topik.index')->with('success', 'Topik berhasil ditambahkan.');
    }

    public function edit(Topik $topik): View
    {
        return view('admin.topik.edit', compact('topik'));
    }

    public function update(Request $request, Topik $topik): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'urutan' => 'nullable|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            if ($topik->gambar) {
                \Storage::disk('public')->delete($topik->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('topik', 'public');
        }

        $topik->update($validated);

        return redirect()->route('admin.topik.index')->with('success', 'Topik berhasil diperbarui.');
    }

    public function destroy(Topik $topik): RedirectResponse
    {
        if ($topik->gambar) {
            \Storage::disk('public')->delete($topik->gambar);
        }

        $topik->delete();

        return redirect()->route('admin.topik.index')->with('success', 'Topik berhasil dihapus.');
    }
}
