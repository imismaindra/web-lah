<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Kategori;
use App\Models\Penulis;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $totalArtikel = Artikel::count();
        $totalKategori = Kategori::count();
        $totalPenulis = Penulis::count();

        $recentArtikel = Artikel::with('kategori')
            ->latest()
            ->take(5)
            ->get();

        $popularArtikel = Artikel::orderByDesc('views')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalArtikel',
            'totalKategori',
            'totalPenulis',
            'recentArtikel',
            'popularArtikel',
        ));
    }
}
