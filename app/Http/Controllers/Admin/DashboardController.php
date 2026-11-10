<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Kategori;
use App\Models\Komentar;
use App\Models\KontakPesan;
use App\Models\Pageview;
use App\Models\Penulis;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
        $hasPageviews = Pageview::exists();

        if ($hasPageviews) {
            $viewsBulanIni = Pageview::where('created_at', '>=', $startOfMonth)
                ->when(! $isAdmin, fn ($q) => $q->whereHas('artikel', fn ($aq) => $aq->where('user_id', auth()->id())))
                ->count();
            $viewsBulanLalu = Pageview::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
                ->when(! $isAdmin, fn ($q) => $q->whereHas('artikel', fn ($aq) => $aq->where('user_id', auth()->id())))
                ->count();
        } else {
            $viewsBulanIni = Artikel::when(! $isAdmin, $ownOnly)->where('created_at', '>=', $startOfMonth)->sum('views');
            $viewsBulanLalu = Artikel::when(! $isAdmin, $ownOnly)->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->sum('views');
        }

        $recentArtikel = Artikel::with('kategori')
            ->when(! $isAdmin, $ownOnly)
            ->latest()
            ->take(5)
            ->get();

        $popularArtikel = Artikel::when(! $isAdmin, $ownOnly)
            ->orderByDesc('views')
            ->take(5)
            ->get();

        // Optimized single-query daily views aggregation (last 7 days)
        $sevenDaysAgo = $now->copy()->subDays(6)->startOfDay();
        $dailyViews = [];

        if ($hasPageviews) {
            $dailyViews = Pageview::where('created_at', '>=', $sevenDaysAgo)
                ->when(! $isAdmin, fn ($q) => $q->whereHas('artikel', fn ($aq) => $aq->where('user_id', auth()->id())))
                ->selectRaw('DATE(created_at) as v_date, count(*) as total')
                ->groupBy('v_date')
                ->pluck('total', 'v_date')
                ->toArray();
        }

        $viewsChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $viewsChart[] = [
                'date' => $date->format('d M'),
                'views' => $dailyViews[$date->toDateString()] ?? 0,
            ];
        }

        // Optimized single-query monthly articles aggregation (last 6 months)
        $sixMonthsAgo = $now->copy()->subMonths(5)->startOfMonth();
        $dateGroupSql = DB::getDriverName() === 'sqlite'
            ? "strftime('%Y-%m', created_at)"
            : 'DATE_FORMAT(created_at, "%Y-%m")';

        $monthlyArticles = Artikel::when(! $isAdmin, $ownOnly)
            ->where('created_at', '>=', $sixMonthsAgo)
            ->selectRaw("{$dateGroupSql} as ym, count(*) as total")
            ->groupBy('ym')
            ->pluck('total', 'ym')
            ->toArray();

        $articlesChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $articlesChart[] = [
                'month' => $month->format('M Y'),
                'count' => $monthlyArticles[$month->format('Y-m')] ?? 0,
            ];
        }

        $pendingKomentars = Komentar::where('status', 'pending')->count();
        $unreadPesan = KontakPesan::unread()->count();

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
            'pendingKomentars',
            'unreadPesan',
        ));
    }
}
