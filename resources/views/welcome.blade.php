<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-adsense-account" content="ca-pub-9007848909516103">

        <title>{{ config('app.name', 'Look at History') }}</title>

        <link rel="icon" href="{{ asset('favicon.ico') }}">

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-[#faf9f7] dark:bg-[#0f0f0e] text-[#171717] dark:text-[#e5e5e3] font-sans antialiased">
        <header class="sticky top-0 z-20 border-b border-stone-200/80 dark:border-white/[0.06] bg-[#faf9f7]/80 dark:bg-[#0f0f0e]/80 backdrop-blur-xl">
            <nav class="mx-auto flex max-w-[1400px] items-center justify-between px-5 py-4 sm:px-8">
                <a href="/" class="flex items-center gap-3">
                    <img src="{{ asset('logo_LAH.jpg') }}" alt="{{ config('app.name', 'Look at History') }}" class="h-9 w-9 rounded-full object-cover ring-1 ring-stone-200 dark:ring-white/10">
                    <span class="font-serif text-lg font-bold tracking-tight">Look at History</span>
                </a>
                <div class="hidden items-center gap-1 text-sm font-medium sm:flex">
                    <a href="#" class="rounded-lg bg-stone-900 px-3 py-1.5 text-white dark:bg-white dark:text-stone-900">Beranda</a>
                    <a href="#artikel" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Artikel</a>
                    <a href="#kategori" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Kategori</a>
                    <a href="#tentang" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Tentang</a>
                </div>
                <div class="flex items-center gap-2 text-sm font-medium">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="rounded-lg bg-stone-900 px-4 py-2 text-white dark:bg-white dark:text-stone-900">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="rounded-lg bg-stone-900 px-4 py-2 text-white dark:bg-white dark:text-stone-900">Daftar</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>
        </header>

        <main class="mx-auto max-w-[1400px] px-5 sm:px-8">
            <section class="pt-12 pb-6 sm:pt-16 sm:pb-8">
                <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-400 dark:text-stone-500">Blog Sejarah Dunia</p>
                        <h1 class="mt-3 font-serif text-3xl font-bold leading-[1.1] tracking-tight sm:text-4xl lg:text-5xl">
                            Masa Lalu yang<br class="hidden sm:block"> Membentuk Masa Kini
                        </h1>
                        <p class="mt-3 max-w-md text-sm leading-relaxed text-stone-500 dark:text-stone-400">
                            Artikel sejarah dunia yang ringkas dan terpercaya — dari peradaban kuno hingga konflik modern.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="#artikel" class="rounded-full bg-stone-900 px-4 py-1.5 text-xs font-semibold text-white dark:bg-white dark:text-stone-900">Semua</a>
                        @forelse ($kategoris as $kategori)
                            <a href="{{ route('kategori.show', $kategori) }}" class="rounded-full border border-stone-200 px-4 py-1.5 text-xs font-semibold text-stone-500 transition hover:border-stone-300 hover:text-stone-900 dark:border-white/10 dark:text-stone-400 dark:hover:border-white/20 dark:hover:text-white">{{ $kategori->nama }}</a>
                        @empty
                            <span class="text-xs text-stone-300 dark:text-stone-600">Belum ada kategori</span>
                        @endforelse
                    </div>
                </div>
            </section>

            <section class="py-8 sm:py-10">
                <h2 class="font-serif text-xl font-bold tracking-tight">Perjalanan Waktu</h2>
                <div class="mt-5 flex gap-4 overflow-x-auto pb-4 scrollbar-hide">
                    @forelse ($eras as $era)
                        <div class="group relative h-64 w-56 flex-shrink-0 overflow-hidden rounded-2xl sm:w-64">
                            @if ($era->gambar)
                                <img src="{{ asset('storage/' . $era->gambar) }}" alt="{{ $era->nama }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-110" loading="lazy">
                            @else
                                <img src="https://picsum.photos/seed/era-{{ $era->slug }}/400/500" alt="{{ $era->nama }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-110" loading="lazy">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 p-5">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-white/60">{{ $era->periode }}</p>
                                <h3 class="mt-1 font-serif text-lg font-bold text-white">{{ $era->nama }}</h3>
                            </div>
                        </div>
                    @empty
                        <div class="flex h-64 w-full flex-shrink-0 items-center justify-center rounded-2xl border border-dashed border-stone-200 text-sm text-stone-400 dark:border-white/[0.06] dark:text-stone-500">Belum ada era</div>
                    @endforelse
                </div>
            </section>

            <div class="grid gap-8 lg:grid-cols-[1fr_320px]">
                <div>
                    @if ($featuredArtikel)
                        <a href="{{ route('artikel.show', $featuredArtikel) }}" class="group block overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
                            <div class="grid sm:grid-cols-[1fr_1.2fr]">
                                <div class="relative overflow-hidden bg-stone-100 dark:bg-stone-800/50">
                                    @if ($featuredArtikel->gambar)
                                        <img src="{{ asset('storage/' . $featuredArtikel->gambar) }}" alt="{{ $featuredArtikel->judul }}" class="h-64 w-full object-cover transition duration-700 group-hover:scale-105 sm:h-full" loading="lazy">
                                    @else
                                        <img src="https://picsum.photos/seed/featured-{{ $featuredArtikel->id }}/800/600" alt="{{ $featuredArtikel->judul }}" class="h-64 w-full object-cover transition duration-700 group-hover:scale-105 sm:h-full" loading="lazy">
                                    @endif
                                    <div class="absolute top-4 left-4">
                                        <span class="rounded-full bg-stone-900/80 px-3 py-1 text-[10px] font-bold tracking-wide text-white uppercase backdrop-blur-sm">Unggulan</span>
                                    </div>
                                </div>
                                <div class="flex flex-col justify-center p-6 sm:p-8">
                                    <div class="flex items-center gap-2.5 text-xs font-semibold">
                                        <span class="text-[#1e3a5f] dark:text-[#5b9bd5]">{{ $featuredArtikel->kategori->nama ?? 'Umum' }}</span>
                                        <span class="text-stone-300 dark:text-stone-600">&middot;</span>
                                        <span class="text-stone-400 dark:text-stone-500">{{ $featuredArtikel->created_at->format('d M Y') }}</span>
                                    </div>
                                    <h2 class="mt-4 font-serif text-2xl font-bold leading-snug tracking-tight group-hover:text-[#1e3a5f] sm:text-3xl dark:group-hover:text-[#5b9bd5]">
                                        {{ $featuredArtikel->judul }}
                                    </h2>
                                    <p class="mt-3 text-sm leading-relaxed text-stone-500 dark:text-stone-400">
                                        {{ $featuredArtikel->ringkasan ?? Str::limit(strip_tags($featuredArtikel->konten), 180) }}
                                    </p>
                                    <p class="mt-5 text-sm font-semibold text-[#1e3a5f] transition group-hover:translate-x-0.5 dark:text-[#5b9bd5]">Baca selengkapnya &rarr;</p>
                                </div>
                            </div>
                        </a>
                    @endif

                    <div id="artikel" class="mt-10">
                        <h2 class="font-serif text-xl font-bold tracking-tight">Artikel Terbaru</h2>

                        <div class="mt-6 grid gap-5 sm:grid-cols-2">
                            @forelse ($latestArtikels as $artikel)
                                <a href="{{ route('artikel.show', $artikel) }}" class="group overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
                                    <div class="relative overflow-hidden bg-stone-100 dark:bg-stone-800/50">
                                        @if ($artikel->gambar)
                                            <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}" class="h-44 w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                                        @else
                                            <img src="https://picsum.photos/seed/artikel-{{ $artikel->id }}/600/400" alt="{{ $artikel->judul }}" class="h-44 w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                                        @endif
                                    </div>
                                    <div class="p-5">
                                        <div class="flex items-center gap-2 text-xs font-semibold">
                                            <span class="text-[#1e3a5f] dark:text-[#5b9bd5]">{{ $artikel->kategori->nama ?? 'Umum' }}</span>
                                            <span class="text-stone-300 dark:text-stone-600">&middot;</span>
                                            <span class="text-stone-400 dark:text-stone-500">{{ $artikel->created_at->format('d M Y') }}</span>
                                        </div>
                                        <h3 class="mt-3 font-serif text-base font-bold leading-snug group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">
                                            {{ $artikel->judul }}
                                        </h3>
                                        <p class="mt-2 text-xs leading-relaxed text-stone-500 dark:text-stone-400">
                                            {{ $artikel->ringkasan ?? Str::limit(strip_tags($artikel->konten), 120) }}
                                        </p>
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-2 rounded-2xl border border-dashed border-stone-200 bg-white p-8 text-center dark:border-white/[0.06] dark:bg-[#171716]">
                                    <p class="text-sm text-stone-400 dark:text-stone-500">Belum ada artikel yang dipublikasikan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <aside class="space-y-6 lg:sticky lg:top-20 lg:self-start">
                    <div class="rounded-2xl border border-stone-200/60 bg-white p-6 dark:border-white/[0.06] dark:bg-[#171716]">
                        <div id="kategori" class="mb-4">
                            <h2 class="font-serif text-base font-bold">Kategori</h2>
                        </div>
                        <ul class="space-y-0.5 text-sm">
                            @forelse ($kategoris as $kategori)
                                <li>
                                    <a href="#" class="flex items-center justify-between rounded-lg px-3 py-2 transition hover:bg-stone-50 dark:hover:bg-white/[0.03]">
                                        <span class="text-stone-600 dark:text-stone-300">{{ $kategori->nama }}</span>
                                        <span class="text-xs font-semibold text-stone-400 dark:text-stone-500">{{ $kategori->artikel_count }}</span>
                                    </a>
                                </li>
                            @empty
                                <li class="px-3 py-2 text-xs text-stone-400 dark:text-stone-500">Belum ada kategori</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="rounded-2xl border border-stone-200/60 bg-white p-6 dark:border-white/[0.06] dark:bg-[#171716]">
                        <h2 class="mb-4 font-serif text-base font-bold">Populer</h2>
                        <ul class="space-y-4">
                            @forelse ($popularArtikels as $index => $artikel)
                                <li>
                                    <a href="{{ route('artikel.show', $artikel) }}" class="group flex gap-3.5">
                                        <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-md bg-stone-100 font-serif text-xs font-bold text-stone-400 group-hover:bg-[#1e3a5f] group-hover:text-white dark:bg-white/[0.05] dark:text-stone-500 dark:group-hover:bg-[#5b9bd5] dark:group-hover:text-[#0f0f0e]">{{ $index + 1 }}</span>
                                        <div>
                                            <p class="text-sm font-semibold leading-snug group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">{{ $artikel->judul }}</p>
                                            <p class="mt-0.5 text-[11px] text-stone-400 dark:text-stone-500">{{ number_format($artikel->views) }} dibaca</p>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li class="py-4 text-center text-xs text-stone-400 dark:text-stone-500">Belum ada data</li>
                            @endforelse
                        </ul>
                    </div>

                    <div id="tentang" class="rounded-2xl border border-stone-200/60 bg-white p-6 dark:border-white/[0.06] dark:bg-[#171716]">
                        <h2 class="font-serif text-base font-bold">Tentang Blog Ini</h2>
                        <p class="mt-2 text-sm leading-relaxed text-stone-500 dark:text-stone-400">
                            Look at History menyajikan artikel sejarah dunia secara ringkas dan terpercaya,
                            dari peradaban kuno hingga konflik modern.
                        </p>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="mt-4 inline-block rounded-lg bg-stone-900 px-4 py-2 text-xs font-semibold text-white transition hover:bg-stone-800 dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200">
                                Mulai Belajar
                            </a>
                        @endif
                    </div>
                </aside>
            </div>

            <section class="py-10 sm:py-14">
                <h2 class="font-serif text-xl font-bold tracking-tight">Jelajahi Topik</h2>
                <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
                    @forelse ($topiks as $index => $topik)
                        <div class="group relative overflow-hidden rounded-2xl {{ $index === 0 ? 'col-span-2 row-span-2 h-72 sm:h-80' : 'h-36 sm:h-[calc(2rem+160px-1rem)]' }}">
                            @if ($topik->gambar)
                                <img src="{{ asset('storage/' . $topik->gambar) }}" alt="{{ $topik->nama }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                            @else
                                <img src="https://picsum.photos/seed/topic-{{ $topik->slug }}/400/300" alt="{{ $topik->nama }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                            <div class="absolute bottom-0 left-0 {{ $index === 0 ? 'p-6' : 'p-4' }}">
                                <h3 class="font-serif font-bold text-white {{ $index === 0 ? 'text-2xl' : 'text-sm' }}">{{ $topik->nama }}</h3>
                                @if ($index === 0 && $topik->deskripsi)
                                    <p class="mt-1 text-sm text-white/70">{{ $topik->deskripsi }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 row-span-2 flex h-72 items-center justify-center rounded-2xl border border-dashed border-stone-200 text-sm text-stone-400 sm:h-80 dark:border-white/[0.06] dark:text-stone-500">Belum ada topik</div>
                    @endforelse
                </div>
            </section>

            <section class="overflow-hidden rounded-2xl bg-stone-900 px-6 py-12 sm:px-12 sm:py-16 dark:bg-white/[0.04]">
                <div class="mx-auto max-w-xl text-center">
                    <h2 class="font-serif text-2xl font-bold tracking-tight text-white sm:text-3xl dark:text-[#e5e5e3]">Tidak Ketinggalan Cerita</h2>
                    <p class="mt-3 text-sm leading-relaxed text-stone-400 dark:text-stone-500">
                        Dapatkan artikel sejarah dunia terbaru langsung di inbox kamu. Tanpa spam, kapan saja.
                    </p>
                    @if (session('success'))
                        <div class="mt-6 rounded-xl border border-emerald-400/20 bg-emerald-400/10 p-4 text-sm font-medium text-emerald-300">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->has('email'))
                        <div class="mt-6 rounded-xl border border-red-400/20 bg-red-400/10 p-4 text-sm font-medium text-red-300">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('newsletter.subscribe') }}" class="mt-6 flex flex-col gap-3 sm:flex-row">
                        @csrf
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="email@kamu.com" class="flex-1 rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-stone-500 focus:border-[#5b9bd5] focus:outline-none focus:ring-1 focus:ring-[#5b9bd5] dark:border-stone-700 dark:bg-stone-800 dark:text-[#e5e5e3] dark:placeholder:text-stone-500">
                        <button type="submit" class="rounded-lg bg-white px-6 py-3 text-sm font-semibold text-stone-900 transition hover:bg-stone-200 dark:bg-[#5b9bd5] dark:text-[#0f0f0e] dark:hover:bg-[#7ab3e0]">Berlangganan</button>
                    </form>
                    <p class="mt-3 text-[11px] text-stone-500 dark:text-stone-600">Kami hormati privasi kamu. Berhenti kapan saja.</p>
                </div>
            </section>
        </main>

        <footer class="mt-16 border-t border-stone-200/80 dark:border-white/[0.06]">
            <div class="mx-auto flex max-w-[1400px] flex-col items-center justify-between gap-4 px-5 py-8 text-sm text-stone-400 sm:flex-row sm:px-8 dark:text-stone-500">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Look at History') }}.</p>
                <p>Belajar Sejarah Dunia</p>
            </div>
        </footer>
    </body>
</html>