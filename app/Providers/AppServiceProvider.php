<?php

namespace App\Providers;

use App\Models\Era;
use App\Models\Kategori;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('partials.site-footer', function ($view) {
            $footerKategoris = rescue(function () {
                return Kategori::whereHas('artikel', fn ($q) => $q->published())
                    ->withCount(['artikel' => fn ($q) => $q->published()])
                    ->orderByDesc('artikel_count')
                    ->take(5)
                    ->get();
            }, collect(), false);

            $footerEras = rescue(function () {
                return Era::whereHas('artikel', fn ($q) => $q->published())
                    ->orderBy('urutan')
                    ->take(5)
                    ->get();
            }, collect(), false);

            $view->with(compact('footerKategoris', 'footerEras'));
        });
    }
}
