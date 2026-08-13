<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

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
                        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-400 dark:text-stone-500">Blog Sejarah</p>
                        <h1 class="mt-3 font-serif text-3xl font-bold leading-[1.1] tracking-tight sm:text-4xl lg:text-5xl">
                            Cerita di Balik<br class="hidden sm:block"> Perjalanan Bangsa
                        </h1>
                        <p class="mt-3 max-w-md text-sm leading-relaxed text-stone-500 dark:text-stone-400">
                            Artikel sejarah yang ringkas dan terpercaya — dari peradaban kuno hingga era modern.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="#" class="rounded-full bg-stone-900 px-4 py-1.5 text-xs font-semibold text-white dark:bg-white dark:text-stone-900">Semua</a>
                        <a href="#" class="rounded-full border border-stone-200 px-4 py-1.5 text-xs font-semibold text-stone-500 transition hover:border-stone-300 hover:text-stone-900 dark:border-white/10 dark:text-stone-400 dark:hover:border-white/20 dark:hover:text-white">Prasejarah</a>
                        <a href="#" class="rounded-full border border-stone-200 px-4 py-1.5 text-xs font-semibold text-stone-500 transition hover:border-stone-300 hover:text-stone-900 dark:border-white/10 dark:text-stone-400 dark:hover:border-white/20 dark:hover:text-white">Hindu–Buddha</a>
                        <a href="#" class="rounded-full border border-stone-200 px-4 py-1.5 text-xs font-semibold text-stone-500 transition hover:border-stone-300 hover:text-stone-900 dark:border-white/10 dark:text-stone-400 dark:hover:border-white/20 dark:hover:text-white">Islam</a>
                        <a href="#" class="rounded-full border border-stone-200 px-4 py-1.5 text-xs font-semibold text-stone-500 transition hover:border-stone-300 hover:text-stone-900 dark:border-white/10 dark:text-stone-400 dark:hover:border-white/20 dark:hover:text-white">Kolonial</a>
                        <a href="#" class="rounded-full border border-stone-200 px-4 py-1.5 text-xs font-semibold text-stone-500 transition hover:border-stone-300 hover:text-stone-900 dark:border-white/10 dark:text-stone-400 dark:hover:border-white/20 dark:hover:text-white">Kemerdekaan</a>
                    </div>
                </div>
            </section>

            <section class="py-8 sm:py-10">
                <h2 class="font-serif text-xl font-bold tracking-tight">Perjalanan Waktu</h2>
                <div class="mt-5 flex gap-4 overflow-x-auto pb-4 scrollbar-hide">
                    <a href="#" class="group relative h-64 w-56 flex-shrink-0 overflow-hidden rounded-2xl sm:w-64">
                        <img src="https://picsum.photos/seed/era-prasejarah/400/500" alt="Prasejarah" class="h-full w-full object-cover transition duration-700 group-hover:scale-110" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-5">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-white/60">± 2,5 juta tahun lalu</p>
                            <h3 class="mt-1 font-serif text-lg font-bold text-white">Prasejarah</h3>
                        </div>
                    </a>
                    <a href="#" class="group relative h-64 w-56 flex-shrink-0 overflow-hidden rounded-2xl sm:w-64">
                        <img src="https://picsum.photos/seed/era-hindu-buddha/400/500" alt="Hindu–Buddha" class="h-full w-full object-cover transition duration-700 group-hover:scale-110" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-5">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-white/60">4 – 15 M</p>
                            <h3 class="mt-1 font-serif text-lg font-bold text-white">Hindu–Buddha</h3>
                        </div>
                    </a>
                    <a href="#" class="group relative h-64 w-56 flex-shrink-0 overflow-hidden rounded-2xl sm:w-64">
                        <img src="https://picsum.photos/seed/era-islam-nusantara/400/500" alt="Islam" class="h-full w-full object-cover transition duration-700 group-hover:scale-110" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-5">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-white/60">13 – 19 M</p>
                            <h3 class="mt-1 font-serif text-lg font-bold text-white">Islam</h3>
                        </div>
                    </a>
                    <a href="#" class="group relative h-64 w-56 flex-shrink-0 overflow-hidden rounded-2xl sm:w-64">
                        <img src="https://picsum.photos/seed/era-kolonial/400/500" alt="Kolonial" class="h-full w-full object-cover transition duration-700 group-hover:scale-110" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-5">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-white/60">16 – 20 M</p>
                            <h3 class="mt-1 font-serif text-lg font-bold text-white">Kolonial</h3>
                        </div>
                    </a>
                    <a href="#" class="group relative h-64 w-56 flex-shrink-0 overflow-hidden rounded-2xl sm:w-64">
                        <img src="https://picsum.photos/seed/era-kemerdekaan/400/500" alt="Kemerdekaan" class="h-full w-full object-cover transition duration-700 group-hover:scale-110" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-5">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-white/60">1945 – sekarang</p>
                            <h3 class="mt-1 font-serif text-lg font-bold text-white">Kemerdekaan</h3>
                        </div>
                    </a>
                </div>
            </section>

            <div class="grid gap-8 lg:grid-cols-[1fr_320px]">
                <div>
                    <a href="{{ route('artikel.sriwijaya') }}" class="group block overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
                        <div class="grid sm:grid-cols-[1fr_1.2fr]">
                            <div class="relative overflow-hidden bg-stone-100 dark:bg-stone-800/50">
                                <img src="https://picsum.photos/seed/history-sriwijaya/800/600" alt="Sriwijaya" class="h-64 w-full object-cover transition duration-700 group-hover:scale-105 sm:h-full" loading="lazy">
                                <div class="absolute top-4 left-4">
                                    <span class="rounded-full bg-stone-900/80 px-3 py-1 text-[10px] font-bold tracking-wide text-white uppercase backdrop-blur-sm">Unggulan</span>
                                </div>
                            </div>
                            <div class="flex flex-col justify-center p-6 sm:p-8">
                                <div class="flex items-center gap-2.5 text-xs font-semibold">
                                    <span class="text-[#1e3a5f] dark:text-[#5b9bd5]">Hindu–Buddha</span>
                                    <span class="text-stone-300 dark:text-stone-600">·</span>
                                    <span class="text-stone-400 dark:text-stone-500">12 Mei 2026</span>
                                </div>
                                <h2 class="mt-4 font-serif text-2xl font-bold leading-snug tracking-tight group-hover:text-[#1e3a5f] sm:text-3xl dark:group-hover:text-[#5b9bd5]">
                                    Sriwijaya: Raksasa Maritim di Selat Malaka
                                </h2>
                                <p class="mt-3 text-sm leading-relaxed text-stone-500 dark:text-stone-400">
                                    Selama lebih dari enam abad, Kerajaan Sriwijaya menguasai jalur perdagangan laut
                                    Nusantara. Bagaimana kerajaan tanpa batas wilayah yang jelas ini mampu menjadi
                                    pusat ekonomi dan agama Buddha di Asia Tenggara?
                                </p>
                                <p class="mt-5 text-sm font-semibold text-[#1e3a5f] transition group-hover:translate-x-0.5 dark:text-[#5b9bd5]">Baca selengkapnya &rarr;</p>
                            </div>
                        </div>
                    </a>

                    <div id="artikel" class="mt-10">
                        <h2 class="font-serif text-xl font-bold tracking-tight">Artikel Terbaru</h2>

                        <div class="mt-6 grid gap-5 sm:grid-cols-2">
                            <a href="#" class="group overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
                                <div class="relative overflow-hidden bg-stone-100 dark:bg-stone-800/50">
                                    <img src="https://picsum.photos/seed/history-sangiran/600/400" alt="Sangiran" class="h-44 w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                                </div>
                                <div class="p-5">
                                    <div class="flex items-center gap-2 text-xs font-semibold">
                                        <span class="text-[#1e3a5f] dark:text-[#5b9bd5]">Prasejarah</span>
                                        <span class="text-stone-300 dark:text-stone-600">·</span>
                                        <span class="text-stone-400 dark:text-stone-500">8 Mei 2026</span>
                                    </div>
                                    <h3 class="mt-3 font-serif text-base font-bold leading-snug group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">
                                        Sangiran: Jejak Homo erectus di Nusantara
                                    </h3>
                                    <p class="mt-2 text-xs leading-relaxed text-stone-500 dark:text-stone-400">
                                        Situs warisan dunia tempat ribuan fosil manusia purba ditemukan.
                                    </p>
                                </div>
                            </a>

                            <a href="#" class="group overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
                                <div class="relative overflow-hidden bg-stone-100 dark:bg-stone-800/50">
                                    <img src="https://picsum.photos/seed/history-borobudur/600/400" alt="Borobudur" class="h-44 w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                                </div>
                                <div class="p-5">
                                    <div class="flex items-center gap-2 text-xs font-semibold">
                                        <span class="text-[#1e3a5f] dark:text-[#5b9bd5]">Hindu–Buddha</span>
                                        <span class="text-stone-300 dark:text-stone-600">·</span>
                                        <span class="text-stone-400 dark:text-stone-500">2 Mei 2026</span>
                                    </div>
                                    <h3 class="mt-3 font-serif text-base font-bold leading-snug group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">
                                        Borobudur: Keajaiban Dunia dari Dinasti Syailendra
                                    </h3>
                                    <p class="mt-2 text-xs leading-relaxed text-stone-500 dark:text-stone-400">
                                        Satu juta batu, 2.672 panel relief, dan makna tersembunyi di balik stupa terbesar.
                                    </p>
                                </div>
                            </a>

                            <a href="#" class="group overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
                                <div class="relative overflow-hidden bg-stone-100 dark:bg-stone-800/50">
                                    <img src="https://picsum.photos/seed/history-islam-nusantara/600/400" alt="Islam Nusantara" class="h-44 w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                                </div>
                                <div class="p-5">
                                    <div class="flex items-center gap-2 text-xs font-semibold">
                                        <span class="text-[#1e3a5f] dark:text-[#5b9bd5]">Islam</span>
                                        <span class="text-stone-300 dark:text-stone-600">·</span>
                                        <span class="text-stone-400 dark:text-stone-500">26 April 2026</span>
                                    </div>
                                    <h3 class="mt-3 font-serif text-base font-bold leading-snug group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">
                                        Masuknya Islam ke Nusantara: Damai atau Perdagangan?
                                    </h3>
                                    <p class="mt-2 text-xs leading-relaxed text-stone-500 dark:text-stone-400">
                                        Perdebatan para sejarawan tentang jalur masuk Islam ke Nusantara.
                                    </p>
                                </div>
                            </a>

                            <a href="#" class="group overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
                                <div class="relative overflow-hidden bg-stone-100 dark:bg-stone-800/50">
                                    <img src="https://picsum.photos/seed/history-diponegoro/600/400" alt="Diponegoro" class="h-44 w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                                </div>
                                <div class="p-5">
                                    <div class="flex items-center gap-2 text-xs font-semibold">
                                        <span class="text-[#1e3a5f] dark:text-[#5b9bd5]">Kolonial</span>
                                        <span class="text-stone-300 dark:text-stone-600">·</span>
                                        <span class="text-stone-400 dark:text-stone-500">19 April 2026</span>
                                    </div>
                                    <h3 class="mt-3 font-serif text-base font-bold leading-snug group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">
                                        Perang Diponegoro: Perlawanan Terpanjang Abad ke-19
                                    </h3>
                                    <p class="mt-2 text-xs leading-relaxed text-stone-500 dark:text-stone-400">
                                        Lima tahun perang besar yang melahirkan strategi perang gerilya.
                                    </p>
                                </div>
                            </a>

                            <a href="#" class="group overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
                                <div class="relative overflow-hidden bg-stone-100 dark:bg-stone-800/50">
                                    <img src="https://picsum.photos/seed/history-sumpah-pemuda/600/400" alt="Sumpah Pemuda" class="h-44 w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                                </div>
                                <div class="p-5">
                                    <div class="flex items-center gap-2 text-xs font-semibold">
                                        <span class="text-[#1e3a5f] dark:text-[#5b9bd5]">Pergerakan</span>
                                        <span class="text-stone-300 dark:text-stone-600">·</span>
                                        <span class="text-stone-400 dark:text-stone-500">12 April 2026</span>
                                    </div>
                                    <h3 class="mt-3 font-serif text-base font-bold leading-snug group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">
                                        Sumpah Pemuda 1928: Sebuah Ikrar Bangsa
                                    </h3>
                                    <p class="mt-2 text-xs leading-relaxed text-stone-500 dark:text-stone-400">
                                        Satu tanah air, satu bangsa, satu bahasa — bagaimana satu kongres mengubah arah perjuangan.
                                    </p>
                                </div>
                            </a>

                            <a href="#" class="group overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
                                <div class="relative overflow-hidden bg-stone-100 dark:bg-stone-800/50">
                                    <img src="https://picsum.photos/seed/history-proklamasi/600/400" alt="Proklamasi" class="h-44 w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                                </div>
                                <div class="p-5">
                                    <div class="flex items-center gap-2 text-xs font-semibold">
                                        <span class="text-[#1e3a5f] dark:text-[#5b9bd5]">Kemerdekaan</span>
                                        <span class="text-stone-300 dark:text-stone-600">·</span>
                                        <span class="text-stone-400 dark:text-stone-500">5 April 2026</span>
                                    </div>
                                    <h3 class="mt-3 font-serif text-base font-bold leading-snug group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">
                                        Detik-Detik Proklamasi 17 Agustus 1945
                                    </h3>
                                    <p class="mt-2 text-xs leading-relaxed text-stone-500 dark:text-stone-400">
                                        Dari Rengasdengklok hingga pembacaan teks proklamasi: 48 jam yang menentukan.
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="mt-10 flex items-center justify-between border-t border-stone-200/80 pt-6 text-sm font-semibold dark:border-white/[0.06]">
                        <span class="text-stone-400 dark:text-stone-500">&larr; Artikel baru</span>
                        <a href="#" class="text-[#1e3a5f] transition hover:text-[#16304a] dark:text-[#5b9bd5] dark:hover:text-[#7ab3e0]">Artikel lama &rarr;</a>
                    </div>
                </div>

                <aside class="space-y-6 lg:sticky lg:top-20 lg:self-start">
                    <div class="rounded-2xl border border-stone-200/60 bg-white p-6 dark:border-white/[0.06] dark:bg-[#171716]">
                        <div id="kategori" class="mb-4">
                            <h2 class="font-serif text-base font-bold">Kategori</h2>
                        </div>
                        <ul class="space-y-0.5 text-sm">
                            <li><a href="#" class="flex items-center justify-between rounded-lg px-3 py-2 transition hover:bg-stone-50 dark:hover:bg-white/[0.03]"><span class="text-stone-600 dark:text-stone-300">Prasejarah</span><span class="text-xs font-semibold text-stone-400 dark:text-stone-500">1</span></a></li>
                            <li><a href="#" class="flex items-center justify-between rounded-lg px-3 py-2 transition hover:bg-stone-50 dark:hover:bg-white/[0.03]"><span class="text-stone-600 dark:text-stone-300">Hindu–Buddha</span><span class="text-xs font-semibold text-stone-400 dark:text-stone-500">2</span></a></li>
                            <li><a href="#" class="flex items-center justify-between rounded-lg px-3 py-2 transition hover:bg-stone-50 dark:hover:bg-white/[0.03]"><span class="text-stone-600 dark:text-stone-300">Islam</span><span class="text-xs font-semibold text-stone-400 dark:text-stone-500">1</span></a></li>
                            <li><a href="#" class="flex items-center justify-between rounded-lg px-3 py-2 transition hover:bg-stone-50 dark:hover:bg-white/[0.03]"><span class="text-stone-600 dark:text-stone-300">Kolonial</span><span class="text-xs font-semibold text-stone-400 dark:text-stone-500">1</span></a></li>
                            <li><a href="#" class="flex items-center justify-between rounded-lg px-3 py-2 transition hover:bg-stone-50 dark:hover:bg-white/[0.03]"><span class="text-stone-600 dark:text-stone-300">Pergerakan</span><span class="text-xs font-semibold text-stone-400 dark:text-stone-500">1</span></a></li>
                            <li><a href="#" class="flex items-center justify-between rounded-lg px-3 py-2 transition hover:bg-stone-50 dark:hover:bg-white/[0.03]"><span class="text-stone-600 dark:text-stone-300">Kemerdekaan</span><span class="text-xs font-semibold text-stone-400 dark:text-stone-500">1</span></a></li>
                        </ul>
                    </div>

                    <div class="rounded-2xl border border-stone-200/60 bg-white p-6 dark:border-white/[0.06] dark:bg-[#171716]">
                        <h2 class="mb-4 font-serif text-base font-bold">Populer</h2>
                        <ul class="space-y-4">
                            <li>
                                <a href="{{ route('artikel.sriwijaya') }}" class="group flex gap-3.5">
                                    <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-md bg-stone-100 font-serif text-xs font-bold text-stone-400 group-hover:bg-[#1e3a5f] group-hover:text-white dark:bg-white/[0.05] dark:text-stone-500 dark:group-hover:bg-[#5b9bd5] dark:group-hover:text-[#0f0f0e]">1</span>
                                    <div>
                                        <p class="text-sm font-semibold leading-snug group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">Sriwijaya: Raksasa Maritim di Selat Malaka</p>
                                        <p class="mt-0.5 text-[11px] text-stone-400 dark:text-stone-500">12.480 dibaca</p>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="group flex gap-3.5">
                                    <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-md bg-stone-100 font-serif text-xs font-bold text-stone-400 group-hover:bg-[#1e3a5f] group-hover:text-white dark:bg-white/[0.05] dark:text-stone-500 dark:group-hover:bg-[#5b9bd5] dark:group-hover:text-[#0f0f0e]">2</span>
                                    <div>
                                        <p class="text-sm font-semibold leading-snug group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">Detik-Detik Proklamasi 17 Agustus 1945</p>
                                        <p class="mt-0.5 text-[11px] text-stone-400 dark:text-stone-500">9.731 dibaca</p>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="group flex gap-3.5">
                                    <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-md bg-stone-100 font-serif text-xs font-bold text-stone-400 group-hover:bg-[#1e3a5f] group-hover:text-white dark:bg-white/[0.05] dark:text-stone-500 dark:group-hover:bg-[#5b9bd5] dark:group-hover:text-[#0f0f0e]">3</span>
                                    <div>
                                        <p class="text-sm font-semibold leading-snug group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">Borobudur: Keajaiban Dunia dari Dinasti Syailendra</p>
                                        <p class="mt-0.5 text-[11px] text-stone-400 dark:text-stone-500">8.204 dibaca</p>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div id="tentang" class="rounded-2xl border border-stone-200/60 bg-white p-6 dark:border-white/[0.06] dark:bg-[#171716]">
                        <h2 class="font-serif text-base font-bold">Tentang Blog Ini</h2>
                        <p class="mt-2 text-sm leading-relaxed text-stone-500 dark:text-stone-400">
                            Look at History menyajikan artikel sejarah secara ringkas dan terpercaya,
                            untuk siapa saja yang ingin memahami masa lalu.
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
                    <a href="#" class="group relative col-span-2 row-span-2 h-72 overflow-hidden rounded-2xl sm:h-80">
                        <img src="https://picsum.photos/seed/topic-maritim/800/600" alt="Maritim" class="h-full w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-6">
                            <h3 class="font-serif text-2xl font-bold text-white">Maritim</h3>
                            <p class="mt-1 text-sm text-white/70">Kapal, pelabuhan, dan jalur perdagangan.</p>
                        </div>
                    </a>
                    <a href="#" class="group relative h-36 overflow-hidden rounded-2xl sm:h-[calc(2rem+160px-1rem)]">
                        <img src="https://picsum.photos/seed/topic-candi/400/300" alt="Candi & Arsitektur" class="h-full w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-4">
                            <h3 class="font-serif text-sm font-bold text-white">Candi & Arsitektur</h3>
                        </div>
                    </a>
                    <a href="#" class="group relative h-36 overflow-hidden rounded-2xl sm:h-[calc(2rem+160px-1rem)]">
                        <img src="https://picsum.photos/seed/topic-perang/400/300" alt="Perang & Perlawanan" class="h-full w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-4">
                            <h3 class="font-serif text-sm font-bold text-white">Perang & Perlawanan</h3>
                        </div>
                    </a>
                    <a href="#" class="group relative h-36 overflow-hidden rounded-2xl sm:h-[calc(2rem+160px-1rem)]">
                        <img src="https://picsum.photos/seed/topic-budaya/400/300" alt="Budaya & Adat" class="h-full w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-4">
                            <h3 class="font-serif text-sm font-bold text-white">Budaya & Adat</h3>
                        </div>
                    </a>
                    <a href="#" class="group relative h-36 overflow-hidden rounded-2xl sm:h-[calc(2rem+160px-1rem)]">
                        <img src="https://picsum.photos/seed/topic-tekno/400/300" alt="Teknologi Kuno" class="h-full w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-4">
                            <h3 class="font-serif text-sm font-bold text-white">Teknologi Kuno</h3>
                        </div>
                    </a>
                </div>
            </section>

            <section class="overflow-hidden rounded-2xl bg-stone-900 px-6 py-12 sm:px-12 sm:py-16 dark:bg-white/[0.04]">
                <div class="mx-auto max-w-xl text-center">
                    <h2 class="font-serif text-2xl font-bold tracking-tight text-white sm:text-3xl dark:text-[#e5e5e3]">Tidak Ketinggalan Cerita</h2>
                    <p class="mt-3 text-sm leading-relaxed text-stone-400 dark:text-stone-500">
                        Dapatkan artikel sejarah terbaru langsung di inbox kamu. Tanpa spam, kapan saja.
                    </p>
                    <form class="mt-6 flex flex-col gap-3 sm:flex-row">
                        <input type="email" placeholder="email@kamu.com" class="flex-1 rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-stone-500 focus:border-[#5b9bd5] focus:outline-none focus:ring-1 focus:ring-[#5b9bd5] dark:border-stone-700 dark:bg-stone-800 dark:text-[#e5e5e3] dark:placeholder:text-stone-500">
                        <button type="submit" class="rounded-lg bg-white px-6 py-3 text-sm font-semibold text-stone-900 transition hover:bg-stone-200 dark:bg-[#5b9bd5] dark:text-[#0f0f0e] dark:hover:bg-[#7ab3e0]">Berlangganan</button>
                    </form>
                    <p class="mt-3 text-[11px] text-stone-500 dark:text-stone-600">Kami hormati privasi kamu. Berhenti kapan saja.</p>
                </div>
            </section>
        </main>

        <footer class="mt-16 border-t border-stone-200/80 dark:border-white/[0.06]">
            <div class="mx-auto flex max-w-[1400px] flex-col items-center justify-between gap-4 px-5 py-8 text-sm text-stone-400 sm:flex-row sm:px-8 dark:text-stone-500">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Look at History') }}.</p>
                <p>Belajar Sejarah</p>
            </div>
        </footer>
    </body>
</html>