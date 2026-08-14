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
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        $totalArtikel = Artikel::count();
        $totalArtikelBulanIni = Artikel::where('created_at', '>=', $startOfMonth)->count();
        $totalArtikelBulanLalu = Artikel::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();

        $totalKategori = Kategori::count();
        $totalPenulis = Penulis::count();

        $totalViews = Artikel::sum('views');
        $viewsBulanIni = Artikel::where('created_at', '>=', $startOfMonth)->sum('views');
        $viewsBulanLalu = Artikel::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->sum('views');

        $recentArtikel = Artikel::with('kategori')
            ->latest()
            ->take(5)
            ->get();

        $popularArtikel = Artikel::orderByDesc('views')
            ->take(5)
            ->get();

        // Chart data: views per day last 7 days
        $viewsChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i)->startOfDay();
            $views = Artikel::whereDate('created_at', $date)->sum('views');
            $viewsChart[] = [
                'date' => $date->format('d M'),
                'views' => $views,
            ];
        }

        // Chart data: articles per month last 6 months
        $articlesChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i)->startOfMonth();
            $count = Artikel::whereBetween('created_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])->count();
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
