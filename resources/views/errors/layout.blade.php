<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        @include('partials.theme-init')
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">

        <title>@yield('title') | Look at History</title>

        <link rel="icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-[#faf9f7] dark:bg-[#0f0f0e] text-[#171717] dark:text-[#e5e5e3] font-sans antialiased">
        @include('partials.site-header')

        <main id="main" class="mx-auto flex min-h-[70vh] max-w-[1400px] items-center justify-center px-5 py-16 sm:px-8">
            <div class="w-full max-w-md text-center">
                <p class="font-mono text-6xl font-bold tracking-tight text-stone-300 dark:text-stone-600">@yield('code')</p>
                <h1 class="mt-4 font-serif text-2xl font-bold tracking-tight">@yield('title')</h1>
                <p class="mt-2 text-sm leading-relaxed text-stone-500 dark:text-stone-400">@yield('message')</p>
                <a href="/" class="mt-8 inline-block rounded-lg bg-stone-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-stone-800 active:scale-[0.98] dark:bg-white dark:text-stone-900 dark:hover:bg-stone-100">
                    Kembali ke beranda
                </a>
            </div>
        </main>

        @include('partials.site-footer')
    </body>
</html>
