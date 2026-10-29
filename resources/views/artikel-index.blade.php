<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        @include('partials.theme-init')
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-adsense-account" content="ca-pub-9007848909516103">

        <title>Semua Artikel Sejarah — {{ config('app.name', 'Look at History') }}</title>

        @include('partials.seo', [
            'title' => 'Semua Artikel Sejarah | Look at History',
            'description' => 'Jelajahi semua artikel sejarah dunia di Look at History: peristiwa, tokoh, dan peradaban dari berbagai era. Dari perang dunia hingga peradaban kuno.',
            'image' => asset('logo_LAH.jpg'),
            'url' => route('artikel.index'),
            'section' => 'Artikel',
            'keywords' => 'artikel sejarah, sejarah dunia, peristiwa sejarah, tokoh sejarah, peradaban kuno, sejarah indonesia',
        ])
        @php
            $indexBreadcrumb = [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => rtrim(config('app.url'), '/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Artikel', 'item' => route('artikel.index')],
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
        @include('partials.site-header', ['active' => 'artikel'])

        <main class="mx-auto max-w-[1400px] px-5 sm:px-8">
            {{-- Hero --}}
            <section class="pt-12 pb-8 sm:pt-16">
                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-400 dark:text-stone-500">Artikel</p>
                <h1 class="mt-3 font-serif text-3xl font-bold leading-[1.1] tracking-tight sm:text-4xl lg:text-5xl">Semua Artikel</h1>
                <p class="mt-4 max-w-xl text-base leading-relaxed text-stone-500 dark:text-stone-400">
                    Peristiwa, tokoh, dan peradaban masa lalu — disusun ringkas, akurat, dan mudah dipahami.
                </p>
            </section>

            {{-- Filter --}}
            <form method="GET" action="{{ route('artikel.index') }}" class="flex flex-wrap gap-3">
                <label class="sr-only" for="f-kategori">Kategori</label>
                <select id="f-kategori" name="kategori" onchange="this.form.submit()" class="rounded-lg border border-stone-200 bg-white px-3 py-2 text-sm text-stone-600 dark:border-white/10 dark:bg-[#171716] dark:text-stone-300">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoris as $k)
                        <option value="{{ $k->slug }}" @selected($kategoriId === $k->id)>{{ $k->nama }}</option>
                    @endforeach
                </select>
                <label class="sr-only" for="f-era">Era</label>
                <select id="f-era" name="era" onchange="this.form.submit()" class="rounded-lg border border-stone-200 bg-white px-3 py-2 text-sm text-stone-600 dark:border-white/10 dark:bg-[#171716] dark:text-stone-300">
                    <option value="">Semua Era</option>
                    @foreach ($eras as $e)
                        <option value="{{ $e->slug }}" @selected($eraId === $e->id)>{{ $e->nama }}</option>
                    @endforeach
                </select>
                <label class="sr-only" for="f-topik">Topik</label>
                <select id="f-topik" name="topik" onchange="this.form.submit()" class="rounded-lg border border-stone-200 bg-white px-3 py-2 text-sm text-stone-600 dark:border-white/10 dark:bg-[#171716] dark:text-stone-300">
                    <option value="">Semua Topik</option>
                    @foreach ($topiks as $t)
                        <option value="{{ $t->slug }}" @selected($topikId === $t->id)>{{ $t->nama }}</option>
                    @endforeach
                </select>
                <label class="sr-only" for="f-urut">Urutan</label>
                <select id="f-urut" name="urut" onchange="this.form.submit()" class="rounded-lg border border-stone-200 bg-white px-3 py-2 text-sm text-stone-600 dark:border-white/10 dark:bg-[#171716] dark:text-stone-300">
                    <option value="" @selected(blank(request('urut')))>Terbaru</option>
                    <option value="populer" @selected(request('urut') === 'populer')>Populer</option>
                </select>
            </form>

            {{-- Articles --}}
            @if ($artikels->isEmpty())
                <div class="rounded-2xl border border-dashed border-stone-200 bg-white p-12 text-center dark:border-white/[0.06] dark:bg-[#171716]">
                    <svg class="mx-auto h-12 w-12 text-stone-300 dark:text-stone-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                    <p class="mt-3 text-sm text-stone-500 dark:text-stone-400">Belum ada artikel.</p>
                </div>
            @else
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($artikels as $artikel)
                        <a href="{{ route('artikel.show', $artikel) }}" class="group overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
                            <div class="relative overflow-hidden bg-stone-100 dark:bg-stone-800/50">
                                @if ($artikel->gambar)
                                    <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}" class="h-44 w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                                @else
                                    <img src="{{ asset('placeholder.svg') }}" alt="{{ $artikel->judul }}" class="h-44 w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                                @endif
                            </div>
                            <div class="p-5">
                                <div class="flex items-center gap-2 text-xs font-semibold">
                                    <span class="text-stone-400 dark:text-stone-500">{{ $artikel->created_at->translatedFormat('d M Y') }}</span>
                                    @if ($artikel->kategori)
                                        <span class="text-stone-300 dark:text-stone-600">&middot;</span>
                                        <span class="text-[#1e3a5f] dark:text-[#5b9bd5]">{{ $artikel->kategori->nama }}</span>
                                    @endif
                                </div>
                                <h3 class="mt-3 font-serif text-base font-bold leading-snug group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">
                                    {{ $artikel->judul }}
                                </h3>
                                <p class="mt-2 text-xs leading-relaxed text-stone-500 dark:text-stone-400">
                                    {{ $artikel->ringkasan ?? Str::limit(strip_tags($artikel->konten), 120) }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>

                @if ($artikels->hasPages())
                    <div class="mt-10 flex justify-center">
                        {{ $artikels->links() }}
                    </div>
                @endif
            @endif
        </main>

        @include('partials.site-footer')
    </body>
</html>