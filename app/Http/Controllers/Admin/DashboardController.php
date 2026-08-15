<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Kategori;
use App\Models\Penulis;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $isAdmin = auth()->user()->hasRole('admin');
        $ownOnly = fn ($query) => $query->where('user_id', auth()->id());

        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        $totalArtikel = Artikel::when(! $isAdmin, $ownOnly)->count();
        $totalArtikelBulanIni = Artikel::when(! $isAdmin, $ownOnly)->where('created_at', '>=', $startOfMonth)->count();
        $totalArtikelBulanLalu = Artikel::when(! $isAdmin, $ownOnly)->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();

        $totalKategori = Kategori::count();
        $totalPenulis = Penulis::count();

        $totalViews = Artikel::when(! $isAdmin, $ownOnly)->sum('views');
        $viewsBulanIni = Artikel::when(! $isAdmin, $ownOnly)->where('created_at', '>=', $startOfMonth)->sum('views');
        $viewsBulanLalu = Artikel::when(! $isAdmin, $ownOnly)->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->sum('views');

        $recentArtikel = Artikel::with('kategori')
            ->when(! $isAdmin, $ownOnly)
            ->latest()
            ->take(5)
            ->get();

        $popularArtikel = Artikel::when(! $isAdmin, $ownOnly)
            ->orderByDesc('views')
            ->take(5)
            ->get();

        // Chart data: views per day last 7 days
        $viewsChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i)->startOfDay();
            $views = Artikel::when(! $isAdmin, $ownOnly)->whereDate('created_at', $date)->sum('views');
            $viewsChart[] = [
                'date' => $date->format('d M'),
                'views' => $views,
            ];
        }

        // Chart data: articles per month last 6 months
        $articlesChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i)->startOfMonth();
            $count = Artikel::when(! $isAdmin, $ownOnly)->whereBetween('created_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])->count();
            $articlesChart[] = [
                'month' => $month->format('M Y'),
                'count' => $count,
            ];
        }

        return view('admin.dashboard', compact(
            'totalArtikel',
            'totalArtikelBulanIni',
            'totalArtikelBulanLalu',
            'totalKategori',
            'totalPenulis',
            'totalViews',
            'viewsBulanIni',
            'viewsBulanLalu',
            'recentArtikel',
            'popularArtikel',
            'viewsChart',
            'articlesChart',
        ));
    }
}
