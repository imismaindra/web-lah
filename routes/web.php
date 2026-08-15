<?php

use App\Http\Controllers\Admin\ArtikelController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EraController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\PenulisController;
use App\Http\Controllers\Admin\TopikController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\SearchController;
use App\Models\Artikel;
use App\Models\Era;
use App\Models\Kategori;
use App\Models\Penulis;
use App\Models\Topik;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $featuredArtikel = Artikel::with('kategori')
        ->published()
        ->latest()
        ->first();

    $latestArtikels = Artikel::with('kategori')
        ->published()
        ->latest()
        ->skip(1)
        ->take(6)
        ->get();

    $kategoris = Kategori::withCount(['artikel' => function ($q) {
        $q->published();
    }])->orderByDesc('artikel_count')->get();

    $popularArtikels = Artikel::published()
        ->orderByDesc('views')
        ->take(5)
        ->get();

    $eras = Era::withCount(['artikel' => function ($q) {
        $q->published();
    }])->orderBy('urutan')->get();

    $topiks = Topik::withCount(['artikel' => function ($q) {
        $q->published();
    }])->orderBy('urutan')->get();

    return view('welcome', compact(
        'featuredArtikel',
        'latestArtikels',
        'kategoris',
        'popularArtikels',
        'eras',
        'topiks',
    ));
});

Route::get('/tentang', function () {
    $statistik = [
        'artikel' => Artikel::published()->count(),
        'kategori' => Kategori::count(),
        'era' => Era::count(),
        'topik' => Topik::count(),
        'pembaca' => Artikel::published()->sum('views'),
    ];

    return view('tentang', compact('statistik'));
})->name('tentang');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/newsletter', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

Route::get('/cari', [SearchController::class, 'index'])->name('search');

Route::get('/sitemap.xml', function () {
    $artikels = Artikel::published()->latest()->get();
    $kategoris = Kategori::all();
    $eras = Era::all();
    $topiks = Topik::all();

    $staticUrls = [
        ['url' => url('/'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'daily', 'priority' => '1.0'],
        ['url' => route('tentang'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.5'],
    ];

    $urls = array_merge($staticUrls, $artikels->map(function ($artikel) {
        return [
            'url' => route('artikel.show', $artikel),
            'lastmod' => $artikel->updated_at->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.8',
        ];
    }), $kategoris->map(function ($kategori) {
        return [
            'url' => route('kategori.show', $kategori),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '0.6',
        ];
    }), $eras->map(function ($era) {
        return [
            'url' => route('era.show', $era),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.6',
        ];
    }), $topiks->map(function ($topik) {
        return [
            'url' => route('topik.show', $topik),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.5',
        ];
    }));

    return response()->view('sitemap', compact('urls'))->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::get('/feed', function () {
    $artikels = Artikel::published()->latest()->take(20)->get();

    return response()->view('rss', compact('artikels'))->header('Content-Type', 'application/xml');
})->name('feed');

Route::get('/kategori/{kategori:slug}', function (Kategori $kategori) {
    $artikels = $kategori->artikel()
        ->published()
        ->latest()
        ->paginate(9);

    return view('kategori', compact('kategori', 'artikels'));
})->name('kategori.show');

Route::get('/era/{era:slug}', function (Era $era) {
    $artikels = $era->artikel()
        ->published()
        ->latest()
        ->paginate(9);

    return view('era', compact('era', 'artikels'));
})->name('era.show');

Route::get('/topik/{topik:slug}', function (Topik $topik) {
    $artikels = $topik->artikel()
        ->published()
        ->latest()
        ->paginate(9);

    return view('topik', compact('topik', 'artikels'));
})->name('topik.show');

Route::get('/penulis/{penulis:slug}', function (Penulis $penulis) {
    $artikels = $penulis->artikel()
        ->with('kategori')
        ->published()
        ->latest()
        ->paginate(9);

    $penulis->loadCount(['artikel' => fn ($q) => $q->published()]);

    return view('penulis', compact('penulis', 'artikels'));
})->name('penulis.show');

Route::get('/artikel/{artikel}', function (Artikel $artikel) {
    $artikel->load(['kategori', 'author', 'author.penulis']);
    $artikel->increment('views');

    $relatedArtikels = Artikel::with('kategori')
        ->published()
        ->where('id', '!=', $artikel->id)
        ->latest()
        ->take(3)
        ->get();

    return view('artikel', compact('artikel', 'relatedArtikels'));
})->name('artikel.show');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('kategori', KategoriController::class)->except(['show']);
    Route::resource('artikel', ArtikelController::class)->except(['show']);
    Route::resource('era', EraController::class)->except(['show']);
    Route::resource('topik', TopikController::class)->except(['show']);

    Route::get('/penulis', [PenulisController::class, 'index'])->name('penulis.index');
    Route::get('/penulis/create', [PenulisController::class, 'create'])->name('penulis.create');
    Route::post('/penulis', [PenulisController::class, 'store'])->name('penulis.store');
    Route::delete('/penulis/{user}', [PenulisController::class, 'destroy'])->name('penulis.destroy');

    Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');
});
