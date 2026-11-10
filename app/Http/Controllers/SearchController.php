<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Era;
use App\Models\Kategori;
use App\Models\Topik;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');
        $artikels = collect();
        $total = 0;

        $kategoriId = $request->filled('kategori') ? Kategori::where('slug', trim($request->kategori))->value('id') : null;
        $eraId = $request->filled('era') ? Era::where('slug', trim($request->era))->value('id') : null;
        $topikId = $request->filled('topik') ? Topik::where('slug', trim($request->topik))->value('id') : null;

        if ($query && Str::length($query) >= 2) {
            $artikels = $this->searchArtikels($query, $kategoriId, $eraId, $topikId, $request->urut);
            $total = $artikels->total();
        }

        $kategoris = Kategori::orderBy('nama')->get();
        $eras = Era::orderBy('urutan')->get();
        $topiks = Topik::orderBy('urutan')->get();

        return view('search', compact('artikels', 'query', 'total', 'kategoris', 'eras', 'topiks', 'kategoriId', 'eraId', 'topikId'));
    }

    public function suggest(Request $request): JsonResponse
    {
        $query = $request->get('q');
        if (! $query || Str::length($query) < 2) {
            return response()->json([]);
        }

        $isSqlite = DB::getDriverName() === 'sqlite';
        $builder = Artikel::published()->with('kategori');

        if ($isSqlite) {
            $builder->where('judul', 'LIKE', "%{$query}%");
        } else {
            $sanitized = $this->sanitizeQuery($query);
            $builder->whereRaw('MATCH(judul, ringkasan, konten) AGAINST(? IN BOOLEAN MODE)', [$sanitized]);
        }

        $results = $builder->latest()->take(6)->get()->map(fn ($artikel) => [
            'id' => $artikel->id,
            'judul' => $artikel->judul,
            'slug' => $artikel->slug,
            'url' => route('artikel.show', $artikel),
            'kategori' => $artikel->kategori?->nama,
            'reading_time' => $artikel->reading_time,
        ]);

        return response()->json($results);
    }

    private function searchArtikels(string $query, ?int $kategoriId = null, ?int $eraId = null, ?int $topikId = null, ?string $sort = null)
    {
        $sanitized = $this->sanitizeQuery($query);
        $isSqlite = DB::getDriverName() === 'sqlite';

        $builder = Artikel::published()->with(['kategori', 'era', 'topiks']);

        if ($isSqlite) {
            $words = array_filter(explode(' ', $sanitized));
            $builder->where(function ($q) use ($words) {
                foreach ($words as $word) {
                    $q->orWhere('judul', 'LIKE', "%{$word}%")
                        ->orWhere('ringkasan', 'LIKE', "%{$word}%")
                        ->orWhere('konten', 'LIKE', "%{$word}%");
                }
            });
        } else {
            $builder->whereRaw('MATCH(judul, ringkasan, konten) AGAINST(? IN BOOLEAN MODE)', [$sanitized]);
        }

        $builder->when($kategoriId, fn ($q) => $q->where('kategori_id', $kategoriId))
            ->when($eraId, fn ($q) => $q->where('era_id', $eraId))
            ->when($topikId, fn ($q) => $q->whereRelation('topiks', 'topiks.id', $topikId))
            ->when($sort === 'populer', fn ($q) => $q->orderByDesc('views'), fn ($q) => $q->latest());

        return $builder->paginate(12)->withQueryString();
    }

    private function sanitizeQuery(string $query): string
    {
        $query = preg_replace('/[+\-<>~*()"]/', ' ', $query);
        $words = array_filter(explode(' ', $query));

        return implode(' ', array_map(fn ($w) => "+{$w}*", $words));
    }
}
