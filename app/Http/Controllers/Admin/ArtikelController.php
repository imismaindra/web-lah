<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Era;
use App\Models\Kategori;
use App\Models\Topik;
use App\Services\ImageOptimizer;
use App\Services\SlugService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArtikelController extends Controller
{
    public function __construct(
        private ImageOptimizer $imageOptimizer
    ) {}

    public function index(Request $request): View
    {
        $baseQuery = Artikel::query();

        if (! auth()->user()->hasRole('admin')) {
            $baseQuery->where('user_id', auth()->id());
        }

        $statusCounts = [
            'all' => (clone $baseQuery)->count(),
            'published' => (clone $baseQuery)->where('status', 'published')->count(),
            'draft' => (clone $baseQuery)->where('status', 'draft')->count(),
            'scheduled' => (clone $baseQuery)->where('status', 'scheduled')->count(),
            'archived' => (clone $baseQuery)->where('status', 'archived')->count(),
        ];

        $clusterCounts = [
            'pillar' => (clone $baseQuery)->where('is_pillar', true)->count(),
            'supporting' => (clone $baseQuery)->whereNotNull('pillar_id')->count(),
            'missing_backlink' => (clone $baseQuery)->whereNotNull('pillar_id')
                ->whereDoesntHave('outgoingLinks', function ($sub) {
                    $sub->whereColumn('artikel_links.target_artikel_id', 'artikels.pillar_id');
                })->count(),
        ];

        $totalViews = (clone $baseQuery)->sum('views');

        $query = Artikel::with(['kategori', 'author', 'pillar', 'outgoingLinks']);

        if (! auth()->user()->hasRole('admin')) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('cluster')) {
            if ($request->cluster === 'pillar') {
                $query->where('is_pillar', true);
            } elseif ($request->cluster === 'supporting') {
                $query->whereNotNull('pillar_id');
            } elseif ($request->cluster === 'missing_backlink') {
                $query->whereNotNull('pillar_id')
                    ->whereDoesntHave('outgoingLinks', function ($sub) {
                        $sub->whereColumn('artikel_links.target_artikel_id', 'artikels.pillar_id');
                    });
            }
        }

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

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->sort === 'populer') {
            $query->orderByDesc('views');
        } elseif ($request->sort === 'terlama') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $artikels = $query->paginate(12)->withQueryString();
        $kategoris = Kategori::orderBy('nama')->get();

        return view('admin.artikel.index', compact('artikels', 'statusCounts', 'clusterCounts', 'kategoris', 'totalViews'));
    }

    public function create(): View
    {
        $kategoris = Kategori::orderBy('nama')->get();
        $eras = Era::orderBy('urutan')->get();
        $topiks = Topik::orderBy('urutan')->get();
        $pillars = Artikel::pillars()->orderBy('judul')->get(['id', 'judul', 'slug']);

        return view('admin.artikel.create', compact('kategoris', 'eras', 'topiks', 'pillars'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'era_id' => 'nullable|exists:eras,id',
            'topik_ids' => 'nullable|array',
            'topik_ids.*' => 'exists:topiks,id',
            'is_pillar' => 'nullable|boolean',
            'pillar_id' => 'nullable|exists:artikels,id',
            'judul' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:300',
            'ringkasan' => 'nullable|string|max:1000',
            'konten' => 'required|string',
            'faq' => 'nullable|array|max:20',
            'faq.*.question' => 'required_with:faq|string|max:255',
            'faq.*.answer' => 'required_with:faq|string|max:2000',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:draft,published,scheduled,archived',
            'published_at' => 'nullable|required_if:status,scheduled|date',
        ]);

        $isPillar = $request->boolean('is_pillar');
        $validated['is_pillar'] = $isPillar;
        $validated['pillar_id'] = $isPillar ? null : ($request->filled('pillar_id') ? (int) $request->input('pillar_id') : null);

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $validated['faq'] = collect($validated['faq'] ?? [])
            ->filter(fn ($f) => filled($f['question'] ?? null) && filled($f['answer'] ?? null))
            ->values()->all() ?: null;

        $validated['slug'] = SlugService::generateUnique(
            Artikel::class,
            $request->input('slug'),
            $validated['judul']
        );
        $validated['user_id'] = auth()->id();

        if ($request->hasFile('gambar')) {
            $variants = $this->imageOptimizer->optimizeResponsive($request->file('gambar'), 'artikel');
            $validated['gambar'] = $variants['large'] ?? $variants['original'];
        }

        $topikIds = $request->input('topik_ids', []);
        unset($validated['topik_ids']);

        $artikel = Artikel::create($validated);
        $artikel->topiks()->sync($topikIds);

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Artikel $artikel): View
    {
        abort_unless(auth()->user()->hasRole('admin') || $artikel->user_id === auth()->id(), 403);

        $kategoris = Kategori::orderBy('nama')->get();
        $eras = Era::orderBy('urutan')->get();
        $topiks = Topik::orderBy('urutan')->get();
        $pillars = Artikel::pillars()->where('id', '!=', $artikel->id)->orderBy('judul')->get(['id', 'judul', 'slug']);
        $artikel->load(['topiks', 'pillar', 'outgoingLinks']);

        return view('admin.artikel.edit', compact('artikel', 'kategoris', 'eras', 'topiks', 'pillars'));
    }

    public function update(Request $request, Artikel $artikel): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('admin') || $artikel->user_id === auth()->id(), 403);

        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'era_id' => 'nullable|exists:eras,id',
            'topik_ids' => 'nullable|array',
            'topik_ids.*' => 'exists:topiks,id',
            'is_pillar' => 'nullable|boolean',
            'pillar_id' => 'nullable|exists:artikels,id',
            'judul' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:300',
            'ringkasan' => 'nullable|string|max:1000',
            'konten' => 'required|string',
            'faq' => 'nullable|array|max:20',
            'faq.*.question' => 'required_with:faq|string|max:255',
            'faq.*.answer' => 'required_with:faq|string|max:2000',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:draft,published,scheduled,archived',
            'published_at' => 'nullable|required_if:status,scheduled|date',
        ]);

        $isPillar = $request->boolean('is_pillar');
        $validated['is_pillar'] = $isPillar;
        $validated['pillar_id'] = ($isPillar || (int) $request->input('pillar_id') === (int) $artikel->id)
            ? null
            : ($request->filled('pillar_id') ? (int) $request->input('pillar_id') : null);

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $validated['faq'] = collect($validated['faq'] ?? [])
            ->filter(fn ($f) => filled($f['question'] ?? null) && filled($f['answer'] ?? null))
            ->values()->all() ?: null;

        if ($request->has('slug')) {
            $validated['slug'] = SlugService::generateUnique(
                Artikel::class,
                $request->input('slug'),
                $validated['judul'],
                $artikel->id
            );
        }

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
        abort_unless(auth()->user()->hasRole('admin') || $artikel->user_id === auth()->id(), 403);

        $artikel->delete();

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil dihapus.');
    }

    public function preview(Artikel $artikel): View
    {
        abort_unless(auth()->user()->hasRole('admin') || $artikel->user_id === auth()->id(), 403);

        $artikel->load(['kategori', 'author', 'author.penulis', 'topiks']);

        $isPreview = true;
        $isLiked = false;
        $isBookmarked = false;
        $reaksiCount = $artikel->reaksis()->where('tipe', 'suka')->count();
        $komentarCount = $artikel->komentars()->published()->count();
        $komentars = collect();
        $relatedArtikels = collect();

        return view('artikel', compact(
            'artikel',
            'relatedArtikels',
            'isLiked',
            'isBookmarked',
            'reaksiCount',
            'komentarCount',
            'komentars',
            'isPreview',
        ));
    }
}
