<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/artikel/sriwijaya', function () {
    return view('artikel');
})->name('artikel.sriwijaya');
