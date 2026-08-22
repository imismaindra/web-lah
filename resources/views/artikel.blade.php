<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-adsense-account" content="ca-pub-9007848909516103">
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9007848909516103"
            crossorigin="anonymous"></script>

        <title>{{ $artikel->judul }} — {{ config('app.name', 'Look at History') }}</title>

        @include('partials.seo', [
            'title' => $artikel->judul,
            'description' => $artikel->ringkasan ?? Str::limit(strip_tags($artikel->konten), 160),
            'image' => $artikel->gambar ? asset('storage/' . $artikel->gambar) : asset('logo_LAH.jpg'),
            'url' => route('artikel.show', $artikel),
            'type' => 'article',
            'publishedTime' => $artikel->created_at?->toIso8601String(),
            'modifiedTime' => $artikel->updated_at?->toIso8601String(),
            'author' => $artikel->author->name ?? config('app.name', 'Look at History'),
            'section' => $artikel->kategori->nama ?? 'Umum',
            'tags' => $artikel->topiks?->pluck('nama')->toArray() ?? [],
        ])

        <link rel="icon" href="{{ asset('favicon.ico') }}">

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            .article-body p { text-indent: 1.5em; }
            .article-body p:first-child,
            .article-body h2,
            .article-body h3,
            .article-body blockquote,
            .article-body .key-point,
            .article-body figure { text-indent: 0; }

            .reading-progress {
                position: fixed;
                top: 0;
                left: 0;
                height: 3px;
                background: linear-gradient(90deg, #1e3a5f, #5b9bd5);
                z-index: 50;
                animation: progress-fill linear;
                animation-timeline: scroll(root);
            }

            @keyframes progress-fill {
                from { width: 0%; }
                to { width: 100%; }
            }

            @supports not (animation-timeline: scroll()) {
                .reading-progress { display: none; }
            }

            .toc-link.active {
                color: #1e3a5f;
                background: #1e3a5f0d;
            }

            :is(.dark) .toc-link.active {
                color: #5b9bd5;
                background: #5b9bd50d;
            }

            .grain-overlay {
                position: fixed;
                inset: 0;
                pointer-events: none;
                z-index: 40;
                opacity: 0.025;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
                background-repeat: repeat;
                background-size: 200px 200px;
            }

            .ad-slot {
                min-height: 250px;
                background: linear-gradient(135deg, #f5f5f4 0%, #e7e5e4 100%);
                border: 1px dashed #d6d3d1;
                border-radius: 1rem;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            :is(.dark) .ad-slot {
                background: linear-gradient(135deg, rgba(255,255,255,0.02) 0%, rgba(255,255,255,0.04) 100%);
                border-color: rgba(255,255,255,0.08);
            }

            .ad-slot-label {
                font-size: 11px;
                font-weight: 600;
                letter-spacing: 0.05em;
                text-transform: uppercase;
                color: #a8a29e;
            }

            .article-body img {
                border-radius: 1rem;
                max-width: 100%;
            }

            .article-body h2 {
                font-size: 1.5rem;
                font-weight: 700;
                padding-top: 1rem;
                color: #1c1917;
            }

            :is(.dark) .article-body h2 {
                color: #e5e5e3;
            }

            .article-body h3 {
                font-size: 1.25rem;
                font-weight: 600;
                padding-top: 0.75rem;
                color: #1c1917;
            }

            :is(.dark) .article-body h3 {
                color: #e5e5e3;
            }

            .article-body blockquote {
                border-left: 3px solid #1e3a5f;
                background: rgb(30 58 95 / 0.05);
                padding: 1rem 1.5rem;
                border-radius: 0 1rem 1rem 0;
                font-style: italic;
                color: #57534e;
            }

            :is(.dark) .article-body blockquote {
                border-left-color: #5b9bd5;
                background: rgb(91 155 213 / 0.05);
                color: #a8a29e;
            }

            .article-body figure {
                margin: 2.5rem 0;
            }

            .article-body figcaption {
                margin-top: 0.75rem;
                text-align: center;
                font-size: 0.75rem;
                color: #a8a29e;
            }

            .article-body ul,
            .article-body ol {
                padding-left: 1.5rem;
                margin: 1rem 0;
            }

            .article-body li {
                margin: 0.5rem 0;
            }

            .article-body .ql-align-left { text-align: left; }
            .article-body .ql-align-center { text-align: center; }
            .article-body .ql-align-right { text-align: right; }
            .article-body .ql-align-justify { text-align: justify; }

            .article-body li.ql-indent-1 { padding-left: 2em; }
            .article-body li.ql-indent-2 { padding-left: 4em; }
            .article-body li.ql-indent-3 { padding-left: 6em; }

            .article-body .ql-font-serif { font-family: Georgia, 'Times New Roman', serif; }
            .article-body .ql-font-monospace { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; }
            .article-body .ql-size-small { font-size: 0.75em; }
            .article-body .ql-size-large { font-size: 1.5em; }
            .article-body .ql-size-huge { font-size: 2.5em; }

            .article-body pre {
                background: #f5f5f4;
                border: 1px solid #e7e5e4;
                border-radius: 1rem;
                padding: 1rem 1.25rem;
                overflow-x: auto;
                font-size: 0.875rem;
                line-height: 1.7;
            }

            :is(.dark) .article-body pre {
                background: rgba(255, 255, 255, 0.03);
                border-color: rgba(255, 255, 255, 0.08);
                color: #e5e5e3;
            }

            .article-body iframe.ql-video {
                display: block;
                width: 100%;
                aspect-ratio: 16 / 9;
                border-radius: 1rem;
            }

            .article-body a {
                color: #1e3a5f;
                text-decoration: underline;
                text-underline-offset: 2px;
            }

            :is(.dark) .article-body a {
                color: #5b9bd5;
            }
        </style>
    </head>
    <body class="bg-[#faf9f7] dark:bg-[#0f0f0e] text-[#171717] dark:text-[#e5e5e3] font-sans antialiased">
        <div class="grain-overlay"></div>
        <div class="reading-progress"></div>

        <header class="sticky top-0 z-20 border-b border-stone-200/80 dark:border-white/[0.06] bg-[#faf9f7]/80 dark:bg-[#0f0f0e]/80 backdrop-blur-xl">
            <nav class="mx-auto flex max-w-[1400px] items-center justify-between px-5 py-4 sm:px-8">
                <a href="/" class="flex items-center gap-3">
                    <img src="{{ asset('logo_LAH.jpg') }}" alt="{{ config('app.name', 'Look at History') }}" class="h-9 w-9 rounded-full object-cover ring-1 ring-stone-200 dark:ring-white/10">
                    <span class="font-serif text-lg font-bold tracking-tight">Look at History</span>
                </a>
                <div class="hidden items-center gap-1 text-sm font-medium sm:flex">
                    <a href="/" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Beranda</a>
                    <a href="/#artikel" class="rounded-lg bg-stone-900 px-3 py-1.5 text-white dark:bg-white dark:text-stone-900">Artikel</a>
                    <a href="/#kategori" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Kategori</a>
                    <a href="{{ route('tentang') }}" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Tentang</a>
                </div>
                <div class="hidden sm:flex items-center gap-2">
                    <form action="{{ route('search') }}" method="GET" class="relative">
                        <input
                            type="text"
                            name="q"
                            placeholder="Cari..."
                            class="w-48 sm:w-64 rounded-lg border border-stone-200 bg-white px-3.5 py-1.5 pl-9 text-sm placeholder:text-stone-400 focus:border-[#1e3a5f] focus:outline-none focus:ring-1 focus:ring-[#1e3a5f] dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]"
                        >
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </form>
                </div>
                <div class="flex items-center gap-2 text-sm font-medium">
                    @if (Route::has('login'))
                        @auth
                            @if (auth()->user()->hasRole(['admin', 'penulis']))
                                <a href="{{ route('admin.dashboard') }}" class="rounded-lg bg-stone-900 px-4 py-2 text-white dark:bg-white dark:text-stone-900">Panel</a>
                            @endif
                            <a href="{{ route('profil.edit') }}" class="rounded-lg bg-stone-900 px-4 py-2 text-white dark:bg-white dark:text-stone-900">Profil</a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Masuk</a>
                        @endauth
                    @endif
                </div>
            </nav>
        </header>

        <main>
            {{-- Hero --}}
            <section class="relative overflow-hidden">
                <div class="absolute inset-0">
                    @if ($artikel->gambar)
                        <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}" class="h-full w-full object-cover" loading="eager">
                    @else
                        <img src="https://picsum.photos/seed/artikel-hero-{{ $artikel->id }}/1600/900" alt="{{ $artikel->judul }}" class="h-full w-full object-cover" loading="eager">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-[#faf9f7] via-[#faf9f7]/60 to-transparent dark:from-[#0f0f0e] dark:via-[#0f0f0e]/60"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-[#faf9f7]/80 to-transparent dark:from-[#0f0f0e]/80"></div>
                </div>

                <div class="relative mx-auto max-w-[1400px] px-5 pt-8 pb-16 sm:px-8 sm:pt-12 sm:pb-24">
                    <a href="/" class="inline-flex items-center gap-1.5 text-sm font-semibold text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                        Kembali
                    </a>

                    <div class="mt-10 max-w-2xl sm:mt-14">
                        <div class="flex items-center gap-2.5 text-xs font-semibold">
                            <span class="rounded-full bg-[#1e3a5f]/10 px-3 py-1 text-[#1e3a5f] dark:bg-[#5b9bd5]/10 dark:text-[#5b9bd5]">{{ $artikel->kategori->nama ?? 'Umum' }}</span>
                            <span class="text-stone-300 dark:text-stone-600">&middot;</span>
                            <span class="text-stone-400 dark:text-stone-500">{{ $artikel->created_at->format('d M Y') }}</span>
                            <span class="text-stone-300 dark:text-stone-600">&middot;</span>
                            <span class="text-stone-400 dark:text-stone-500">{{ ceil(str_word_count(strip_tags($artikel->konten)) / 200) }} menit baca</span>
                        </div>

                        <h1 class="mt-5 font-serif text-3xl font-bold leading-[1.1] tracking-tight sm:text-4xl lg:text-5xl">
                            {{ $artikel->judul }}
                        </h1>

                        @if ($artikel->ringkasan)
                            <p class="mt-5 max-w-xl text-base leading-relaxed text-stone-500 dark:text-stone-400">
                                {{ $artikel->ringkasan }}
                            </p>
                        @endif

                        <div class="mt-8 flex items-center gap-3.5">
                            @if ($artikel->author->penulis)
                                <a href="{{ route('penulis.show', $artikel->author->penulis) }}" class="flex items-center gap-3.5 group">
                                    @if ($artikel->author->penulis->avatar)
                                        <img src="{{ asset('storage/' . $artikel->author->penulis->avatar) }}" alt="{{ $artikel->author->penulis->nama }}" class="h-11 w-11 rounded-full object-cover">
                                    @else
                                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-stone-100 font-serif text-sm font-bold text-stone-500 dark:bg-white/[0.05] dark:text-stone-400">
                                            {{ substr($artikel->author->name ?? 'A', 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="text-sm">
                                        <p class="font-semibold transition group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">{{ $artikel->author->penulis->nama }}</p>
                                        <p class="text-xs text-stone-400 dark:text-stone-500">Penulis</p>
                                    </div>
                                </a>
                            @else
                                @if ($artikel->author->penulis?->avatar)
                                    <img src="{{ asset('storage/' . $artikel->author->penulis->avatar) }}" alt="{{ $artikel->author->name ?? 'Penulis' }}" class="h-11 w-11 rounded-full object-cover">
                                @else
                                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-stone-100 font-serif text-sm font-bold text-stone-500 dark:bg-white/[0.05] dark:text-stone-400">
                                        {{ substr($artikel->author->name ?? 'A', 0, 1) }}
                                    </div>
                                @endif
                                <div class="text-sm">
                                    <p class="font-semibold">{{ $artikel->author->name ?? 'Redaksi' }}</p>
                                    <p class="text-xs text-stone-400 dark:text-stone-500">Penulis</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            {{-- Article Content + Sidebar --}}
            <div class="mx-auto max-w-[1400px] px-5 sm:px-8">
                <div class="grid gap-12 lg:grid-cols-[1fr_300px]">
                    {{-- Article Body --}}
                    <article class="py-8 sm:py-12">
                        <div class="article-body space-y-6 text-[17px] leading-[1.85] text-stone-600 dark:text-stone-300">
                            {!! $artikel->konten !!}
                        </div>

                        {{-- Author Bio --}}
                        <div class="mt-10 rounded-2xl border border-stone-200/60 bg-white p-6 sm:p-8 dark:border-white/[0.06] dark:bg-[#171716]">
                            <div class="flex items-start gap-4">
                                @if ($artikel->author->penulis)
                                    <a href="{{ route('penulis.show', $artikel->author->penulis) }}">
                                        @if ($artikel->author->penulis->avatar)
                                            <img src="{{ asset('storage/' . $artikel->author->penulis->avatar) }}" alt="{{ $artikel->author->penulis->nama }}" class="h-14 w-14 flex-shrink-0 rounded-full object-cover">
                                        @else
                                            <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-stone-100 font-serif text-lg font-bold text-stone-500 dark:bg-white/[0.05] dark:text-stone-400">
                                                {{ substr($artikel->author->name ?? 'A', 0, 1) }}
                                            </div>
                                        @endif
                                    </a>
                                @else
                                    @if ($artikel->author->penulis?->avatar)
                                        <img src="{{ asset('storage/' . $artikel->author->penulis->avatar) }}" alt="{{ $artikel->author->name ?? 'Penulis' }}" class="h-14 w-14 flex-shrink-0 rounded-full object-cover">
                                    @else
                                        <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-stone-100 font-serif text-lg font-bold text-stone-500 dark:bg-white/[0.05] dark:text-stone-400">
                                            {{ substr($artikel->author->name ?? 'A', 0, 1) }}
                                        </div>
                                    @endif
                                @endif
                                <div>
                                    <p class="font-semibold">{{ $artikel->author->penulis?->nama ?? $artikel->author->name ?? 'Redaksi' }}</p>
                                    <p class="mt-1 text-sm leading-relaxed text-stone-500 dark:text-stone-400">{{ $artikel->author->penulis?->bio ?? 'Menulis tentang sejarah dunia untuk pembaca yang ingin memahami masa lalu.' }}</p>
                                    @if ($artikel->author->penulis)
                                        <a href="{{ route('artikel.index') }}" class="mt-3 inline-block text-sm font-semibold text-[#1e3a5f] transition hover:text-[#16304a] dark:text-[#5b9bd5] dark:hover:text-[#7ab3e0]">Lihat semua artikel &rarr;</a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Share --}}
                        <div class="mt-10">
                            <p class="text-sm font-semibold text-stone-500 dark:text-stone-400 mb-3">Bagikan artikel ini</p>
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="https://api.whatsapp.com/send?text={{ urlencode($artikel->judul . ' ' . route('artikel.show', $artikel)) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full border border-stone-200 px-4 py-2 text-sm font-medium transition hover:border-stone-300 hover:bg-stone-50 dark:border-white/10 dark:hover:border-white/20 dark:hover:bg-white/[0.03]">
                                    <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    WhatsApp
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('artikel.show', $artikel)) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full border border-stone-200 px-4 py-2 text-sm font-medium transition hover:border-stone-300 hover:bg-stone-50 dark:border-white/10 dark:hover:border-white/20 dark:hover:bg-white/[0.03]">
                                    <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    Facebook
                                </a>
                                <a href="https://www.instagram.com/" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full border border-stone-200 px-4 py-2 text-sm font-medium transition hover:border-stone-300 hover:bg-stone-50 dark:border-white/10 dark:hover:border-white/20 dark:hover:bg-white/[0.03]">
                                    <svg class="h-4 w-4 text-pink-600 dark:text-pink-400" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                    Instagram
                                </a>
                                <button type="button" onclick="copyLink(this)" data-url="{{ route('artikel.show', $artikel) }}" class="inline-flex items-center gap-2 rounded-full border border-stone-200 px-4 py-2 text-sm font-medium transition hover:border-stone-300 hover:bg-stone-50 dark:border-white/10 dark:hover:border-white/20 dark:hover:bg-white/[0.03]">
                                    <svg class="h-4 w-4 text-stone-500 dark:text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                                    <span class="copy-label">Salin Link</span>
                                </button>
                            </div>
                        </div>

                        {{-- Reactions --}}
                        <div class="mt-8 flex flex-wrap items-center gap-4">
                            @auth
                                <button type="button" id="btn-suka" data-url="{{ route('reaksi.toggle', $artikel) }}" data-active="{{ $isLiked ? 'true' : 'false' }}" class="inline-flex items-center gap-2 rounded-full border border-stone-200 px-5 py-2.5 text-sm font-semibold transition hover:border-stone-300 dark:border-white/10 dark:hover:border-white/20" aria-pressed="{{ $isLiked ? 'true' : 'false' }}">
                                    <svg id="suka-icon" class="h-4 w-4 transition" viewBox="0 0 24 24" fill="{{ $isLiked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                                    <span>Suka</span>
                                    <span id="suka-count" class="text-stone-400 dark:text-stone-500">{{ $reaksiCount }}</span>
                                </button>
                                <button type="button" id="btn-bookmark" data-url="{{ route('bookmark.toggle', $artikel) }}" data-active="{{ $isBookmarked ? 'true' : 'false' }}" class="inline-flex items-center gap-2 rounded-full border border-stone-200 px-5 py-2.5 text-sm font-semibold transition hover:border-stone-300 dark:border-white/10 dark:hover:border-white/20" aria-pressed="{{ $isBookmarked ? 'true' : 'false' }}">
                                    <svg id="bookmark-icon" class="h-4 w-4 transition" viewBox="0 0 24 24" fill="{{ $isBookmarked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                                    <span id="bookmark-label">{{ $isBookmarked ? 'Tersimpan' : 'Simpan' }}</span>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-full border border-stone-200 px-5 py-2.5 text-sm font-semibold text-stone-500 transition hover:border-stone-300 hover:text-stone-900 dark:border-white/10 dark:text-stone-400 dark:hover:border-white/20 dark:hover:text-white">
                                    Masuk untuk menyukai atau menyimpan artikel ini
                                </a>
                            @endauth
                        </div>

                        {{-- Comments --}}
                        <section id="komentar" class="mt-12">
                            <h2 class="font-serif text-2xl font-bold tracking-tight">Komentar <span class="text-stone-300 dark:text-stone-600">({{ $komentarCount }})</span></h2>

                            @if (session('success'))
                                <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-400">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900/30 dark:bg-red-900/10 dark:text-red-300">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Form --}}
                            <form method="POST" action="{{ route('komentar.store', $artikel) }}" class="mt-6 rounded-2xl border border-stone-200/60 bg-white p-6 dark:border-white/[0.06] dark:bg-[#171716]">
                                @csrf
                                @if (! auth()->check())
                                    <input
                                        type="text"
                                        name="nama"
                                        value="{{ old('nama') }}"
                                        required
                                        placeholder="Nama Anda"
                                        class="mb-3 block w-full rounded-lg border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-900 placeholder:text-stone-400 focus:border-[#1e3a5f] focus:outline-none focus:ring-1 focus:ring-[#1e3a5f] dark:border-white/10 dark:bg-white/[0.03] dark:text-[#e5e5e3] dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]"
                                    >
                                @endif
                                <textarea
                                    name="isi"
                                    rows="4"
                                    required
                                    placeholder="Tulis komentar Anda..."
                                    class="block w-full rounded-lg border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder:text-stone-400 focus:border-[#1e3a5f] focus:outline-none focus:ring-1 focus:ring-[#1e3a5f] dark:border-white/10 dark:bg-white/[0.03] dark:text-[#e5e5e3] dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]"
                                >{{ old('isi') }}</textarea>
                                <div class="mt-4 flex items-center justify-between gap-3">
                                    <p class="text-[11px] text-stone-400 dark:text-stone-500">Komentar sopan dan relevan sangat dihargai.</p>
                                    <button type="submit" class="rounded-lg bg-stone-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-stone-800 active:scale-[0.98] dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200">
                                        Kirim Komentar
                                    </button>
                                </div>
                            </form>

                            {{-- List --}}
                            <div class="mt-8 space-y-6">
                                @forelse ($komentars as $komentar)
                                    <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
                                        <div class="flex items-start gap-3.5">
                                            <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-stone-100 font-serif text-sm font-bold text-stone-500 dark:bg-white/[0.05] dark:text-stone-400">
                                                {{ substr($komentar->displayName(), 0, 1) }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5">
                                                    <p class="text-sm font-semibold">{{ $komentar->displayName() }}</p>
                                                    <span class="text-[11px] text-stone-400 dark:text-stone-500">{{ $komentar->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="mt-1.5 text-sm leading-relaxed text-stone-600 dark:text-stone-300">{{ $komentar->isi }}</p>
                                                <button type="button" class="mt-2 text-xs font-semibold text-[#1e3a5f] transition hover:text-[#16304a] dark:text-[#5b9bd5] dark:hover:text-[#7ab3e0]" data-reply-toggle>Balas</button>
                                            </div>
                                        </div>

                                        {{-- Replies --}}
                                        @if ($komentar->replies->isNotEmpty())
                                            <div class="mt-4 space-y-4 border-l-2 border-stone-100 pl-4 dark:border-white/[0.06]">
                                                @foreach ($komentar->replies as $reply)
                                                    <div class="flex items-start gap-3">
                                                        <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-stone-100 font-serif text-xs font-bold text-stone-500 dark:bg-white/[0.05] dark:text-stone-400">
                                                            {{ substr($reply->displayName(), 0, 1) }}
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5">
                                                                <p class="text-sm font-semibold">{{ $reply->displayName() }}</p>
                                                                <span class="text-[11px] text-stone-400 dark:text-stone-500">{{ $reply->created_at->diffForHumans() }}</span>
                                                            </div>
                                                            <p class="mt-1 text-sm leading-relaxed text-stone-600 dark:text-stone-300">{{ $reply->isi }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- Reply form --}}
                                        <form method="POST" action="{{ route('komentar.store', $artikel) }}" class="mt-3 hidden" data-reply-form>
                                            @csrf
                                            <input type="hidden" name="parent_id" value="{{ $komentar->id }}">
                                            @if (! auth()->check())
                                                <input type="text" name="nama" required placeholder="Nama Anda" class="mb-2 block w-full rounded-lg border border-stone-200 bg-white px-4 py-2 text-sm text-stone-900 placeholder:text-stone-400 focus:border-[#1e3a5f] focus:outline-none focus:ring-1 focus:ring-[#1e3a5f] dark:border-white/10 dark:bg-white/[0.03] dark:text-[#e5e5e3] dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]">
                                            @endif
                                            <div class="flex items-center gap-2">
                                                <textarea name="isi" rows="2" required placeholder="Tulis balasan..." class="flex-1 rounded-lg border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-900 placeholder:text-stone-400 focus:border-[#1e3a5f] focus:outline-none focus:ring-1 focus:ring-[#1e3a5f] dark:border-white/10 dark:bg-white/[0.03] dark:text-[#e5e5e3] dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]"></textarea>
                                                <button type="submit" class="rounded-lg bg-stone-900 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-stone-800 dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200">Balas</button>
                                            </div>
                                        </form>
                                    </div>
                                @empty
                                    <p class="rounded-2xl border border-dashed border-stone-200 p-8 text-center text-sm text-stone-400 dark:border-white/[0.06] dark:text-stone-500">
                                        Belum ada komentar. Jadilah yang pertama.
                                    </p>
                                @endforelse
                            </div>
                        </section>
                    </article>

                    {{-- Sidebar --}}
                    <aside class="hidden lg:block">
                        <div class="sticky top-24 space-y-8">
                            {{-- Sidebar Ad --}}
                            <div>
                                <div class="ad-slot" style="min-height: 250px;">
                                    <span class="ad-slot-label">Iklan</span>
                                </div>
                            </div>

                            {{-- Newsletter --}}
                            <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
                                <h2 class="font-serif text-base font-bold">Tidak Ketinggalan Cerita</h2>
                                <p class="mt-2 text-sm leading-relaxed text-stone-500 dark:text-stone-400">
                                    Artikel sejarah terbaru langsung di inbox kamu.
                                </p>
                                <form id="newsletter-form-sidebar" action="{{ route('newsletter.subscribe') }}" method="POST" class="mt-4 space-y-2.5">
                                    @csrf
                                    <input type="email" name="email" placeholder="email@kamu.com" class="w-full rounded-lg border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-sm placeholder:text-stone-400 focus:border-[#1e3a5f] focus:outline-none focus:ring-1 focus:ring-[#1e3a5f] dark:border-white/10 dark:bg-white/[0.03] dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]">
                                    <button type="submit" class="w-full rounded-lg bg-stone-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-stone-800 dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200">Berlangganan</button>
                                </form>
                                <p class="mt-2 text-[11px] text-stone-400 dark:text-stone-500">Kami hormati privasi kamu.</p>
                            </div>
                        </div>
                    </aside>
                </div>

                {{-- Multiplex Ad --}}
                <div class="my-10">
                    <div class="ad-slot" style="min-height: 250px;">
                        <span class="ad-slot-label">Iklan</span>
                    </div>
                </div>

                {{-- Related Articles --}}
                @if ($relatedArtikels->isNotEmpty())
                    <section class="border-t border-stone-200/80 py-12 dark:border-white/[0.06] sm:py-16">
                        <h2 class="font-serif text-2xl font-bold tracking-tight">Baca Juga</h2>
                        <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($relatedArtikels as $related)
                                <a href="{{ route('artikel.show', $related) }}" class="group overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
                                    <div class="relative overflow-hidden bg-stone-100 dark:bg-stone-800/50">
                                        @if ($related->gambar)
                                            <img src="{{ asset('storage/' . $related->gambar) }}" alt="{{ $related->judul }}" class="h-44 w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                                        @else
                                            <img src="https://picsum.photos/seed/related-{{ $related->id }}/600/400" alt="{{ $related->judul }}" class="h-44 w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                                        @endif
                                    </div>
                                    <div class="p-5">
                                        <div class="flex items-center gap-2 text-xs font-semibold">
                                            <span class="text-[#1e3a5f] dark:text-[#5b9bd5]">{{ $related->kategori->nama ?? 'Umum' }}</span>
                                            <span class="text-stone-300 dark:text-stone-600">&middot;</span>
                                            <span class="text-stone-400 dark:text-stone-500">{{ $related->created_at->format('d M Y') }}</span>
                                        </div>
                                        <h3 class="mt-3 font-serif text-base font-bold leading-snug group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">
                                            {{ $related->judul }}
                                        </h3>
                                        <p class="mt-2 text-xs leading-relaxed text-stone-500 dark:text-stone-400">
                                            {{ $related->ringkasan ?? Str::limit(strip_tags($related->konten), 120) }}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>
        </main>

        <footer class="border-t border-stone-200/80 dark:border-white/[0.06]">
            <div class="mx-auto flex max-w-[1400px] flex-col items-center justify-between gap-4 px-5 py-8 text-sm text-stone-400 sm:flex-row sm:px-8 dark:text-stone-500">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Look at History') }}.</p>
                <div class="flex items-center gap-5">
                    <a href="{{ route('kontak') }}" class="transition hover:text-stone-600 dark:hover:text-stone-300">Kontak</a>
                    <a href="{{ route('privasi') }}" class="transition hover:text-stone-600 dark:hover:text-stone-300">Kebijakan Privasi</a>
                </div>
            </div>
        </footer>

        <script>
            function initNewsletterForm(formId) {
                const form = document.getElementById(formId);
                if (!form) return;
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const btn = form.querySelector('button[type="submit"]');
                    const originalBtnText = btn.textContent;
                    btn.disabled = true;
                    btn.textContent = 'Mendaftar...';

                    try {
                        const formData = new FormData(form);
                        const res = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                            },
                            body: formData,
                        });
                        const data = await res.json();
                        if (data.success) {
                            form.reset();
                            showToast(data.message, 'success');
                        } else {
                            showToast(data.message || 'Terjadi kesalahan', 'error');
                        }
                    } catch {
                        showToast('Terjadi kesalahan jaringan', 'error');
                    } finally {
                        btn.disabled = false;
                        btn.textContent = originalBtnText;
                    }
                });
            }

            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `fixed bottom-6 right-6 z-50 px-4 py-3 rounded-lg text-sm font-medium shadow-lg transition-all duration-300 ${
                    type === 'success'
                        ? 'bg-emerald-500 text-white'
                        : 'bg-red-500 text-white'
                }`;
                toast.textContent = message;
                document.body.appendChild(toast);
                setTimeout(() => toast.style.opacity = '0', 3000);
                setTimeout(() => toast.remove(), 3500);
            }

            function initToggleButton(button, onSuccess) {
                if (!button) return;
                button.addEventListener('click', async () => {
                    const active = button.dataset.active === 'true';
                    button.disabled = true;

                    try {
                        const res = await fetch(button.dataset.url, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            },
                        });

                        if (!res.ok) throw new Error('Gagal');

                        const data = await res.json();
                        button.dataset.active = data.active === undefined ? String(data.bookmarked) : String(data.active);
                        button.setAttribute('aria-pressed', button.dataset.active);
                        onSuccess(data);
                    } catch {
                        showToast('Terjadi kesalahan, silakan coba lagi.', 'error');
                    } finally {
                        button.disabled = false;
                    }
                });
            }

            function copyLink(button) {
                const url = button.dataset.url;
                navigator.clipboard.writeText(url).then(() => {
                    const label = button.querySelector('.copy-label');
                    label.textContent = 'Tersalin!';
                    setTimeout(() => { label.textContent = 'Salin Link'; }, 2000);
                });
            }

            function initReplyToggles() {
                document.querySelectorAll('[data-reply-toggle]').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        const form = btn.closest('.rounded-2xl')?.querySelector('[data-reply-form]');
                        if (form) form.classList.toggle('hidden');
                    });
                });
            }

            function initMetaCsrf() {
                if (!document.querySelector('meta[name="csrf-token"]')) {
                    const meta = document.createElement('meta');
                    meta.name = 'csrf-token';
                    meta.content = document.querySelector('[name="_token"]')?.value || '';
                    document.head.appendChild(meta);
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                initNewsletterForm('newsletter-form');
                initNewsletterForm('newsletter-form-sidebar');
                initMetaCsrf();
                initReplyToggles();

                initToggleButton(document.getElementById('btn-suka'), (data) => {
                    const icon = document.getElementById('suka-icon');
                    if (icon) {
                        icon.setAttribute('fill', data.active ? 'currentColor' : 'none');
                        icon.classList.toggle('text-red-500', data.active);
                    }
                    const count = document.getElementById('suka-count');
                    if (count) count.textContent = data.count;
                });

                initToggleButton(document.getElementById('btn-bookmark'), (data) => {
                    const icon = document.getElementById('bookmark-icon');
                    if (icon) icon.setAttribute('fill', data.bookmarked ? 'currentColor' : 'none');
                    const label = document.getElementById('bookmark-label');
                    if (label) label.textContent = data.bookmarked ? 'Tersimpan' : 'Simpan';
                });
            });
        </script>
    </body>
</html>
