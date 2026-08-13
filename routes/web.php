<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/artikel/sriwijaya', function () {
    return view('artikel');
})->name('artikel.sriwijaya');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/artikel', function () {
        return view('admin.artikel.index');
    })->name('artikel.index');

    Route::get('/artikel/baru', function () {
        return view('admin.artikel.create');
    })->name('artikel.create');

    Route::get('/artikel/{id}/edit', function ($id) {
        return view('admin.artikel.edit', ['id' => $id]);
    })->name('artikel.edit');

    Route::get('/kategori', function () {
        return view('admin.kategori.index');
    })->name('kategori.index');

    Route::get('/kategori/baru', function () {
        return view('admin.kategori.create');
    })->name('kategori.create');

    Route::get('/penulis', function () {
        return view('admin.penulis.index');
    })->name('penulis.index');
});
