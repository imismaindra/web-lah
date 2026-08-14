<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
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

        if ($query && Str::length($query) >= 2) {
            $artikels = $this->searchArtikels($query);
            $total = $artikels->total();
        }

        return view('search', compact('artikels', 'query', 'total'));
    }

    private function searchArtikels(string $query)
    {
        $sanitized = $this->sanitizeQuery($query);
        $isSqlite = DB::getDriverName() === 'sqlite';

        $builder = Artikel::published()->with('kategori')->latest();

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

        return $builder->paginate(12)->withQueryString();
    }

    private function sanitizeQuery(string $query): string
    {
        $query = preg_replace('/[+\-<>~*()"]/', ' ', $query);
        $words = array_filter(explode(' ', $query));

        return implode(' ', array_map(fn ($w) => "+{$w}*", $words));
    }
}
