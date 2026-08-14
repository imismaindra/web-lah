<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Era;
use App\Models\Kategori;
use App\Models\Topik;
use App\Services\ImageOptimizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArtikelController extends Controller
{
    public function __construct(
        private ImageOptimizer $imageOptimizer
    ) {}

    public function index(Request $request): View
    {
        $query = Artikel::with(['kategori', 'author'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $artikels = $query->paginate(12)->withQueryString();

        return view('admin.artikel.index', compact('artikels'));
    }

    public function create(): View
    {
        $kategoris = Kategori::orderBy('nama')->get();
        $eras = Era::orderBy('urutan')->get();
        $topiks = Topik::orderBy('urutan')->get();

        return view('admin.artikel.create', compact('kategoris', 'eras', 'topiks'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'era_id' => 'nullable|exists:eras,id',
            'topik_ids' => 'nullable|array',
            'topik_ids.*' => 'exists:topiks,id',
            'judul' => 'required|string|max:255',
            'ringkasan' => 'nullable|string|max:1000',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:draft,published,archived',
        ]);

        $validated['slug'] = Str::slug($validated['judul']);
        $validated['user_id'] = auth()->id();

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $this->imageOptimizer->optimizeAndStore($request->file('gambar'), 'artikel');
        }

        $topikIds = $request->input('topik_ids', []);
        unset($validated['topik_ids']);

        $artikel = Artikel::create($validated);
        $artikel->topiks()->sync($topikIds);

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Artikel $artikel): View
    {
        $kategoris = Kategori::orderBy('nama')->get();
        $eras = Era::orderBy('urutan')->get();
        $topiks = Topik::orderBy('urutan')->get();
        $artikel->load('topiks');

        return view('admin.artikel.edit', compact('artikel', 'kategoris', 'eras', 'topiks'));
    }

    public function update(Request $request, Artikel $artikel): RedirectResponse
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'era_id' => 'nullable|exists:eras,id',
            'topik_ids' => 'nullable|array',
            'topik_ids.*' => 'exists:topiks,id',
            'judul' => 'required|string|max:255',
            'ringkasan' => 'nullable|string|max:1000',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:draft,published,archived',
        ]);

        if ($request->hasFile('gambar')) {
            if ($artikel->gambar) {
                $this->imageOptimizer->delete($artikel->gambar);
            }
            $validated['gambar'] = $this->imageOptimizer->optimizeAndStore($request->file('gambar'), 'artikel');
        }

        $topikIds = $request->input('topik_ids', []);
        unset($validated['topik_ids']);

        $artikel->update($validated);
        $artikel->topiks()->sync($topikIds);

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Artikel $artikel): RedirectResponse
    {
        if ($artikel->gambar) {
            $this->imageOptimizer->delete($artikel->gambar);
        }

        $artikel->delete();

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil dihapus.');
    }
}
