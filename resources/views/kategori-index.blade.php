<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        @include('partials.theme-init')
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-adsense-account" content="ca-pub-9007848909516103">

        <title>Kategori Artikel Sejarah | {{ config('app.name', 'Look at History') }}</title>

        @include('partials.seo', [
            'title' => 'Kategori Artikel Sejarah | Look at History',
            'description' => 'Jelajahi indeks kategori artikel sejarah dunia: peradaban kuno, konflik militer, tokoh, diplomasi, dan perkembangan zaman.',
            'image' => asset('logo_LAH.jpg'),
            'url' => route('kategori.index'),
            'section' => 'Kategori',
            'keywords' => 'kategori sejarah, peradaban kuno, perang dunia, tokoh sejarah, arsip sejarah, belajar sejarah',
        ])
        @php
            $indexBreadcrumb = [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => rtrim(config('app.url'), '/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Kategori', 'item' => route('kategori.index')],
                ],
            ];
        @endphp
        <script type="application/ld+json">{!! json_encode($indexBreadcrumb, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

        <link rel="icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-[#faf9f7] dark:bg-[#0f0f0e] text-[#171717] dark:text-[#e5e5e3] font-sans antialiased">
        @include('partials.site-header', ['active' => 'kategori'])

        <main id="main" class="mx-auto max-w-[1400px] px-5 sm:px-8">
            <section class="pt-10 pb-8 sm:pt-14">
                <nav aria-label="Breadcrumb" class="mb-6">
                    <ol class="flex flex-wrap items-center gap-1.5 text-xs font-medium text-stone-500 dark:text-stone-400">
                        <li>
                            <a href="/" class="transition hover:text-stone-900 dark:hover:text-white">Beranda</a>
                        </li>
                        <li aria-hidden="true" class="text-stone-300 dark:text-stone-600">
                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        </li>
                        <li class="font-semibold text-stone-900 dark:text-white" aria-current="page">
                            Kategori
                        </li>
                    </ol>
                </nav>

                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-400 dark:text-stone-500">Eksplorasi Tematik</p>
                <h1 class="mt-3 font-serif text-3xl font-bold leading-[1.1] tracking-tight sm:text-4xl lg:text-5xl">Kategori Sejarah</h1>
                <p class="mt-4 max-w-2xl text-base leading-relaxed text-stone-500 dark:text-stone-400">
                    Jelajahi seluruh arsip sejarah berdasarkan topik dan rumpun bahasan terkurasi.
                </p>
            </section>

            @if ($kategoris->isEmpty())
                <div class="rounded-2xl border border-dashed border-stone-200 bg-white p-12 text-center dark:border-white/[0.06] dark:bg-[#171716]">
                    <p class="text-sm text-stone-400 dark:text-stone-500">Belum ada kategori yang dipublikasikan.</p>
                </div>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($kategoris as $kategori)
                        <div class="group flex flex-col justify-between rounded-2xl border border-stone-200/70 bg-white p-6 transition hover:border-stone-300 hover:shadow-[0_8px_24px_rgba(0,0,0,0.04)] dark:border-white/[0.06] dark:bg-[#171716] dark:hover:border-white/10">
                            <div>
                                <div class="flex items-start justify-between gap-3">
                                    <h2 class="font-serif text-xl font-bold tracking-tight text-stone-900 transition group-hover:text-[#1e3a5f] dark:text-white dark:group-hover:text-stone-200">
                                        <a href="{{ route('kategori.show', $kategori) }}">{{ $kategori->nama }}</a>
                                    </h2>
                                    <span class="shrink-0 rounded-full bg-stone-100 px-2.5 py-0.5 text-xs font-semibold text-stone-600 dark:bg-white/10 dark:text-stone-300">
                                        {{ $kategori->artikel_count }} artikel
                                    </span>
                                </div>

                                @if ($kategori->deskripsi)
                                    <p class="mt-3 text-xs leading-relaxed text-stone-500 dark:text-stone-400">
                                        {{ Str::limit($kategori->deskripsi, 120) }}
                                    </p>
                                @endif

                                @if ($kategori->artikel->isNotEmpty())
                                    <div class="mt-5 border-t border-stone-100 pt-4 dark:border-white/[0.04]">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400 dark:text-stone-500">Artikel Terbaru</p>
                                        <ul class="mt-2.5 space-y-2">
                                            @foreach ($kategori->artikel as $art)
                                                <li>
                                                    <a href="{{ route('artikel.show', $art) }}" class="line-clamp-1 font-serif text-xs font-medium text-stone-700 transition hover:text-[#1e3a5f] dark:text-stone-300 dark:hover:text-white">
                                                        {{ $art->judul }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6 border-t border-stone-100 pt-4 dark:border-white/[0.04]">
                                <a href="{{ route('kategori.show', $kategori) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#1e3a5f] transition group-hover:translate-x-0.5 dark:text-stone-300 dark:hover:text-white">
                                    Buka Arsip Kategori
                                    <span>&rarr;</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </main>

        @include('partials.site-footer')
    </body>
</html>
