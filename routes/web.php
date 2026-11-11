<?php

use App\Http\Controllers\Admin\ArtikelController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EraController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\KomentarController as AdminKomentarController;
use App\Http\Controllers\Admin\KontakController as AdminKontakController;
use App\Http\Controllers\Admin\NewsletterController as AdminNewsletterController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\PenulisController;
use App\Http\Controllers\Admin\TopikController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\KomentarController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ReaksiController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SearchController;
use App\Models\Artikel;
use App\Models\Bookmark;
use App\Models\Era;
use App\Models\Kategori;
use App\Models\Penulis;
use App\Models\Reaksi;
use App\Models\Topik;
use App\Models\UrlRedirect;
use App\Services\AnalyticsTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
    }])->whereHas('artikel', fn ($q) => $q->published())->orderByDesc('artikel_count')->get();

    $popularArtikels = Artikel::published()
        ->orderByDesc('views')
        ->take(5)
        ->get();

    $eras = Era::withCount(['artikel' => function ($q) {
        $q->published();
    }])->orderBy('urutan')->get();

    $topiks = Topik::query()
        ->whereHas('artikel', fn ($q) => $q->published())
        ->withCount(['artikel' => fn ($q) => $q->published()])
        ->orderBy('urutan')
        ->get();

    $kategoriSorotan = Kategori::withCount(['artikel' => fn ($q) => $q->published()])
        ->whereHas('artikel', fn ($q) => $q->published())
        ->orderByDesc('artikel_count')->take(3)->get()
        ->load(['artikel' => fn ($q) => $q->published()->latest()->take(3)->with('kategori')]);

    $totalArtikels = Artikel::published()->count();

    return view('welcome', compact(
        'featuredArtikel',
        'latestArtikels',
        'kategoris',
        'popularArtikels',
        'eras',
        'topiks',
        'kategoriSorotan',
        'totalArtikels',
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

Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
Route::post('/kontak', [KontakController::class, 'store'])->middleware('throttle:3,1')->name('kontak.store');

Route::get('/kebijakan-privasi', function () {
    return view('kebijakan-privasi');
})->name('privasi');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::redirect('/panel/login', '/login')->name('panel.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->middleware('throttle:3,1')->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])->middleware('throttle:3,1')->name('password.store');
});

Route::post('/newsletter', [NewsletterController::class, 'subscribe'])->middleware('throttle:3,1')->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
Route::post('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'destroySubscription'])->name('newsletter.unsubscribe.destroy');

Route::get('/cari', [SearchController::class, 'index'])->name('search');
Route::get('/api/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');

Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware('signed')
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.resend');

Route::middleware('auth')->group(function () {
    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');

    Route::get('/bookmark', [BookmarkController::class, 'index'])->name('bookmark.index');
    Route::post('/artikel/{artikel}/bookmark', [BookmarkController::class, 'toggle'])->name('bookmark.toggle');
    Route::post('/artikel/{artikel}/reaksi', [ReaksiController::class, 'toggle'])->name('reaksi.toggle');
});

Route::get('/sitemap.xml', function () {
    $publishedArticles = fn ($query) => $query->published();

    $artikels = Artikel::published()->latest()->get();
    $latestArticleUpdate = $artikels->max('updated_at');

    $kategoris = Kategori::query()
        ->whereHas('artikel', $publishedArticles)
        ->withMax(['artikel as latest_article_updated_at' => $publishedArticles], 'updated_at')
        ->get();

    $eras = Era::query()
        ->whereHas('artikel', $publishedArticles)
        ->withMax(['artikel as latest_article_updated_at' => $publishedArticles], 'updated_at')
        ->get();

    $topiks = Topik::query()
        ->whereHas('artikel', $publishedArticles)
        ->withMax(['artikel as latest_article_updated_at' => $publishedArticles], 'updated_at')
        ->get();

    $penulises = Penulis::query()
        ->whereHas('artikel', $publishedArticles)
        ->withMax(['artikel as latest_article_updated_at' => $publishedArticles], 'updated_at')
        ->get();

    $resolveLastmod = static function ($model) {
        $timestamps = collect([
            $model->updated_at,
            $model->latest_article_updated_at,
        ])->filter();

        if ($timestamps->isEmpty()) {
            return null;
        }

        return $timestamps
            ->map(fn ($timestamp) => Carbon::parse($timestamp))
            ->sort()
            ->last()
            ->toAtomString();
    };

    $staticUrls = [
        [
            'url' => url('/'),
            'lastmod' => $latestArticleUpdate?->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '1.0',
        ],
        [
            'url' => route('artikel.index'),
            'lastmod' => $latestArticleUpdate?->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '0.9',
        ],
        ['url' => route('tentang'), 'changefreq' => 'monthly', 'priority' => '0.5'],
        ['url' => route('kontak'), 'changefreq' => 'monthly', 'priority' => '0.4'],
        ['url' => route('privasi'), 'changefreq' => 'yearly', 'priority' => '0.3'],
    ];

    $articleUrls = $artikels->map(function ($artikel) {
        $entry = [
            'url' => route('artikel.show', $artikel),
            'lastmod' => $artikel->updated_at?->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.8',
        ];

        if ($artikel->gambar) {
            $entry['image'] = [
                'loc' => asset('storage/'.$artikel->gambar),
                'title' => $artikel->judul,
            ];
        }

        return $entry;
    });

    $categoryUrls = $kategoris->map(fn ($kategori) => [
        'url' => route('kategori.show', $kategori),
        'lastmod' => $resolveLastmod($kategori),
        'changefreq' => 'daily',
        'priority' => '0.6',
    ]);

    $eraUrls = $eras->map(fn ($era) => [
        'url' => route('era.show', $era),
        'lastmod' => $resolveLastmod($era),
        'changefreq' => 'weekly',
        'priority' => '0.6',
    ]);

    $topicUrls = $topiks->map(fn ($topik) => [
        'url' => route('topik.show', $topik),
        'lastmod' => $resolveLastmod($topik),
        'changefreq' => 'weekly',
        'priority' => '0.5',
    ]);

    $authorUrls = $penulises->map(fn ($penulis) => [
        'url' => route('penulis.show', $penulis),
        'lastmod' => $resolveLastmod($penulis),
        'changefreq' => 'monthly',
        'priority' => '0.4',
    ]);

    $urls = $articleUrls
        ->concat($staticUrls)
        ->concat($categoryUrls)
        ->concat($eraUrls)
        ->concat($topicUrls)
        ->concat($authorUrls)
        ->values()
        ->all();

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

Route::get('/artikel', function (Request $request) {
    $kategoriId = $request->filled('kategori') ? Kategori::where('slug', trim($request->kategori))->value('id') : null;
    $eraId = $request->filled('era') ? Era::where('slug', trim($request->era))->value('id') : null;
    $topikId = $request->filled('topik') ? Topik::where('slug', trim($request->topik))->value('id') : null;

    $artikels = Artikel::query()
        ->published()
        ->with('kategori')
        ->when($kategoriId, fn ($q) => $q->where('kategori_id', $kategoriId))
        ->when($eraId, fn ($q) => $q->where('era_id', $eraId))
        ->when($topikId, fn ($q) => $q->whereRelation('topiks', 'topiks.id', $topikId))
        ->when($request->urut === 'populer', fn ($q) => $q->orderByDesc('views'), fn ($q) => $q->latest())
        ->paginate(9)
        ->withQueryString();

    $kategoris = Kategori::orderBy('nama')->get();
    $eras = Era::orderBy('urutan')->get();
    $topiks = Topik::query()
        ->whereHas('artikel', fn ($q) => $q->published())
        ->orderBy('urutan')
        ->get();

    return view('artikel-index', compact('artikels', 'kategoris', 'eras', 'topiks', 'kategoriId', 'eraId', 'topikId'));
})->name('artikel.index');

Route::post('/artikel/{artikel}/komentar', [KomentarController::class, 'store'])->middleware('throttle:3,1')->name('komentar.store');

Route::get('/artikel/{artikel}', function (Artikel $artikel, Request $request) {
    $artikel->load(['kategori', 'era', 'topiks', 'author', 'author.penulis']);

    $isPreview = false;
    if (! $artikel->isPublished()) {
        $canPreview = auth()->check() && (auth()->user()->hasRole('admin') || auth()->id() === $artikel->user_id);
        if (! $canPreview && ! $request->hasValidSignature()) {
            abort(404);
        }
        $isPreview = true;
    }

    if (! $isPreview) {
        app(AnalyticsTracker::class)->recordArtikelView($artikel, $request);
    }

    $isLiked = auth()->check() && Reaksi::where('artikel_id', $artikel->id)
        ->where('user_id', auth()->id())
        ->where('tipe', 'suka')
        ->exists();

    $isBookmarked = auth()->check() && Bookmark::where('artikel_id', $artikel->id)
        ->where('user_id', auth()->id())
        ->exists();

    $reaksiCount = $artikel->reaksis()->where('tipe', 'suka')->count();
    $komentarCount = $artikel->komentars()->published()->count();

    $komentars = $artikel->komentars()
        ->published()
        ->with(['user', 'replies' => fn ($q) => $q->published()->with('user')])
        ->whereNull('parent_id')
        ->latest()
        ->get();

    $relatedTopikIds = $artikel->topiks->modelKeys();

    $relatedArtikels = Artikel::published()
        ->where('id', '!=', $artikel->id)
        ->with('kategori')
        ->withCount([
            'topiks as matching_topiks_count' => fn ($query) => $query->whereIn('topiks.id', $relatedTopikIds),
        ])
        ->when($artikel->kategori_id, fn ($query) => $query->orderByRaw(
            'CASE WHEN kategori_id = ? THEN 1 ELSE 0 END DESC',
            [$artikel->kategori_id]
        ))
        ->when($artikel->era_id, fn ($query) => $query->orderByRaw(
            'CASE WHEN era_id = ? THEN 1 ELSE 0 END DESC',
            [$artikel->era_id]
        ))
        ->orderByDesc('matching_topiks_count')
        ->latest('published_at')
        ->take(3)
        ->get();

    if ($relatedArtikels->count() < 3) {
        $fallbackArtikels = Artikel::published()
            ->whereNotIn('id', $relatedArtikels->pluck('id')->push($artikel->id))
            ->with('kategori')
            ->latest('published_at')
            ->take(3 - $relatedArtikels->count())
            ->get();

        $relatedArtikels = $relatedArtikels->concat($fallbackArtikels);
    }

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
})->name('artikel.show');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'penulis'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/artikel/{artikel}/preview', [ArtikelController::class, 'preview'])->name('artikel.preview');
    Route::resource('artikel', ArtikelController::class)->except(['show']);
    Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');

    Route::middleware('admin')->group(function () {
        Route::resource('kategori', KategoriController::class)->except(['show']);
        Route::resource('era', EraController::class)->except(['show']);
        Route::resource('topik', TopikController::class)->except(['show']);

        Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
        Route::post('/pengguna/{user}/approve', [PenggunaController::class, 'approve'])->name('pengguna.approve');
        Route::put('/pengguna/{user}/role', [PenggunaController::class, 'updateRole'])->name('pengguna.role');
        Route::delete('/pengguna/{user}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');

        Route::get('/newsletter', [AdminNewsletterController::class, 'index'])->name('newsletter.index');
        Route::get('/newsletter/create', [AdminNewsletterController::class, 'create'])->name('newsletter.create');
        Route::post('/newsletter', [AdminNewsletterController::class, 'store'])->name('newsletter.store');
        Route::post('/newsletter/test-send', [AdminNewsletterController::class, 'testSend'])->name('newsletter.test-send');
        Route::delete('/newsletter/{subscriber}', [AdminNewsletterController::class, 'destroy'])->name('newsletter.destroy');

        Route::get('/penulis', [PenulisController::class, 'index'])->name('penulis.index');
        Route::get('/penulis/create', [PenulisController::class, 'create'])->name('penulis.create');
        Route::post('/penulis', [PenulisController::class, 'store'])->name('penulis.store');
        Route::get('/penulis/{penulis}/edit', [PenulisController::class, 'edit'])->name('penulis.edit');
        Route::put('/penulis/{penulis}', [PenulisController::class, 'update'])->name('penulis.update');
        Route::delete('/penulis/{user}', [PenulisController::class, 'destroy'])->name('penulis.destroy');

        Route::get('/komentar', [AdminKomentarController::class, 'index'])->name('komentar.index');
        Route::post('/komentar/bulk', [AdminKomentarController::class, 'bulkAction'])->name('komentar.bulk');
        Route::post('/komentar/{komentar}/approve', [AdminKomentarController::class, 'approve'])->name('komentar.approve');
        Route::delete('/komentar/{komentar}', [AdminKomentarController::class, 'destroy'])->name('komentar.destroy');

        Route::get('/kontak', [AdminKontakController::class, 'index'])->name('kontak.index');
        Route::get('/kontak/{pesan}', [AdminKontakController::class, 'show'])->name('kontak.show');
        Route::post('/kontak/{pesan}/toggle-read', [AdminKontakController::class, 'toggleRead'])->name('kontak.toggle-read');
        Route::delete('/kontak/{pesan}', [AdminKontakController::class, 'destroy'])->name('kontak.destroy');
    });
});

Route::fallback(function (Request $request) {
    $path = trim($request->path(), '/');
    $redirect = UrlRedirect::findRedirect($path);

    if ($redirect) {
        $redirect->increment('hits');
        $targetUrl = str_starts_with($redirect->target_path, 'http')
            ? $redirect->target_path
            : url($redirect->target_path);

        return redirect()->to($targetUrl, $redirect->status_code ?? 301);
    }

    abort(404);
});
