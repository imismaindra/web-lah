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
use App\Models\Artikel;
use App\Models\Era;
use App\Models\Kategori;
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

    $eras = Era::orderBy('urutan')->get();

    $topiks = Topik::orderBy('urutan')->get();

    return view('welcome', compact(
        'featuredArtikel',
        'latestArtikels',
        'kategoris',
        'popularArtikels',
        'eras',
        'topiks',
    ));
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/newsletter', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

Route::get('/kategori/{kategori:slug}', function (Kategori $kategori) {
    $artikels = $kategori->artikel()
        ->published()
        ->latest()
        ->paginate(9);

    return view('kategori', compact('kategori', 'artikels'));
})->name('kategori.show');

Route::get('/artikel/{artikel}', function (Artikel $artikel) {
    $artikel->load(['kategori', 'author']);
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
