<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-adsense-account" content="ca-pub-9007848909516103">
        <title>Hasil Pencarian: {{ $query ?? '' }} — {{ config('app.name', 'Look at History') }}</title>
        @include('partials.seo', [
            'title' => 'Hasil Pencarian: ' . ($query ?? ''),
            'description' => $total > 0 ? "Ditemukan {$total} artikel untuk \"{$query}\"" : "Tidak ada hasil untuk \"{$query}\"",
            'noindex' => true,
            'section' => 'Pencarian',
        ])
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
                    <a href="/" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Beranda</a>
                    <a href="{{ route('artikel.index') }}" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Artikel</a>
                    <a href="/#kategori" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Kategori</a>
                    <a href="{{ route('tentang') }}" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Tentang</a>
                </div>
                <div class="flex items-center gap-2 text-sm font-medium">
                    @auth
                        @if (auth()->user()->hasRole(['admin', 'penulis']))
                            <a href="{{ route('admin.dashboard') }}" class="rounded-lg bg-stone-900 px-4 py-2 text-white dark:bg-white dark:text-stone-900">Panel</a>
                        @endif
                        <a href="{{ route('profil.edit') }}" class="rounded-lg bg-stone-900 px-4 py-2 text-white dark:bg-white dark:text-stone-900">Profil</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Masuk</a>
                    @endauth
                </div>
            </nav>
        </header>

        <main class="mx-auto max-w-[1400px] px-5 sm:px-8">
            {{-- Masthead + Search --}}
            <section class="border-b border-stone-200/80 dark:border-white/[0.06] pb-8 pt-10 sm:pt-12">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <p class="flex items-center gap-3 text-[11px] font-bold uppercase tracking-[0.18em] text-stone-400 dark:text-stone-500">
                        <span class="h-px w-10 bg-stone-300 dark:bg-white/15"></span>
                        Pencarian Arsip
                    </p>
                    @if($query)
                        <p class="text-xs font-medium text-stone-400 dark:text-stone-500">
                            {{ $total > 0 ? number_format($total).' artikel ditemukan' : 'Tidak ada hasil' }}
                        </p>
                    @else
                        <p class="text-xs font-medium text-stone-400 dark:text-stone-500">{{ \App\Models\Artikel::published()->count() }} artikel terindeks</p>
                    @endif
                </div>

                <div class="mt-6 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-2xl">
                        @if($query)
                            <h1 class="font-serif text-3xl font-bold leading-[0.95] tracking-tight sm:text-4xl">
                                <span class="text-stone-400 dark:text-stone-500 font-normal text-xl sm:text-2xl block mb-2">Hasil untuk</span>
                                <span class="text-balance">“{{ $query }}”</span>
                            </h1>
                            <p class="mt-3 text-sm leading-relaxed text-stone-500 dark:text-stone-400 max-w-xl">
                                @if($total > 0)
                                    Ditemukan <span class="font-semibold text-stone-700 dark:text-stone-200">{{ $total }}</span> artikel. Diurutkan terbaru — klik untuk membaca lengkap.
                                @else
                                    Tidak ada artikel cocok. Coba kata kunci lain atau telusuri kategori.
                                @endif
                            </p>
                        @else
                            <h1 class="font-serif text-3xl font-bold leading-[0.95] tracking-tight sm:text-4xl text-balance">Cari di arsip sejarah</h1>
                            <p class="mt-3 text-sm leading-relaxed text-stone-500 dark:text-stone-400 max-w-xl">Ketik tokoh, peristiwa, era, atau topik. Minimal 2 karakter.</p>
                        @endif
                    </div>

                    <form method="GET" action="{{ route('search') }}" class="w-full lg:w-[420px] shrink-0">
                        <label for="q" class="sr-only">Kata kunci</label>
                        <div class="relative">
                            <input
                                id="q"
                                type="text"
                                name="q"
                                value="{{ $query ?? '' }}"
                                placeholder="Mis. Perang Dunia, Majapahit, Revolusi..."
                                autofocus
                                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3.5 pl-11 pr-24 text-sm placeholder:text-stone-400 focus:border-[#1e3a5f] focus:outline-none focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10"
                            >
                            <svg class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            <button type="submit" class="absolute right-1.5 top-1/2 -translate-y-1/2 rounded-lg bg-stone-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-stone-800 active:scale-[0.98] dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200">Cari</button>
                        </div>
                        <div class="mt-2.5 flex flex-wrap items-center gap-1.5">
                            <span class="text-[11px] font-semibold text-stone-400 dark:text-stone-500 mr-1">Coba:</span>
                            @foreach(['Perang Dunia','Majapahit','Kolonial','Revolusi'] as $s)
                                <a href="{{ route('search', ['q' => $s]) }}" class="rounded-full border border-stone-200 bg-white px-2.5 py-1 text-xs font-medium text-stone-600 transition hover:border-stone-300 hover:bg-stone-50 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-300 dark:hover:bg-white/[0.06]">{{ $s }}</a>
                            @endforeach
                        </div>
                    </form>
                </div>
            </section>

            @if($query)
                @if($artikels->isNotEmpty())
                    @php $featured = $artikels->first(); $rest = $artikels->slice(1); @endphp
                    <div class="grid gap-10 py-8 lg:grid-cols-[1fr_300px] lg:gap-8">
                        <div>
                            {{-- Featured result --}}
                            <a href="{{ route('artikel.show', $featured) }}" class="group flex flex-col overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716] sm:flex-row">
                                <div class="relative w-full shrink-0 overflow-hidden bg-stone-100 dark:bg-stone-800/50 sm:w-[46%]">
                                    @if($featured->gambar)
                                        <img src="{{ asset('storage/'.$featured->gambar) }}" alt="{{ $featured->judul }}" class="aspect-[16/10] w-full object-cover transition duration-700 group-hover:scale-[1.03] sm:aspect-[4/3] sm:h-full" loading="eager">
                                    @else
                                        <img src="https://picsum.photos/seed/search-feat-{{ $featured->id }}/800/600" alt="{{ $featured->judul }}" class="aspect-[16/10] w-full object-cover transition duration-700 group-hover:scale-[1.03] sm:aspect-[4/3] sm:h-full" loading="eager">
                                    @endif
                                    <span class="absolute left-3 top-3 rounded-full bg-[#1e3a5f] px-2.5 py-1 text-[10px] font-bold uppercase tracking-widest text-white dark:bg-[#5b9bd5] dark:text-[#0f0f0e]">Paling relevan</span>
                                </div>
                                <div class="flex flex-1 flex-col p-6 sm:p-7">
                                    <div class="flex items-center gap-2 text-xs font-semibold">
                                        <span class="text-[#1e3a5f] dark:text-[#5b9bd5]">{{ $featured->kategori->nama ?? 'Umum' }}</span>
                                        <span class="text-stone-300 dark:text-stone-600">&middot;</span>
                                        <span class="font-normal text-stone-400 dark:text-stone-500">{{ $featured->created_at->translatedFormat('d M Y') }}</span>
                                        <span class="text-stone-300 dark:text-stone-600">&middot;</span>
                                        <span class="font-normal text-stone-400 dark:text-stone-500">{{ ceil(str_word_count(strip_tags($featured->konten))/200) }} menit</span>
                                    </div>
                                    <h2 class="mt-3 font-serif text-xl font-bold leading-tight tracking-tight group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5] sm:text-2xl">{{ $featured->judul }}</h2>
                                    <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-stone-500 dark:text-stone-400">{{ $featured->ringkasan ?? Str::limit(strip_tags($featured->konten), 160) }}</p>
                                    <p class="mt-auto pt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-[#1e3a5f] dark:text-[#5b9bd5]">Baca artikel <span class="transition-transform duration-300 group-hover:translate-x-0.5">&rarr;</span></p>
                                </div>
                            </a>

                            {{-- Rest as list --}}
                            <div class="mt-6 divide-y divide-stone-200/70 dark:divide-white/[0.06] overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
                                @foreach($rest as $artikel)
                                    <a href="{{ route('artikel.show', $artikel) }}" class="group flex gap-4 p-4 transition hover:bg-stone-50/70 dark:hover:bg-white/[0.03] sm:p-5">
                                        <div class="hidden h-20 w-28 shrink-0 overflow-hidden rounded-xl bg-stone-100 dark:bg-stone-800/50 sm:block">
                                            @if($artikel->gambar)
                                                <img src="{{ asset('storage/'.$artikel->gambar) }}" alt="{{ $artikel->judul }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                                            @else
                                                <img src="https://picsum.photos/seed/search-{{ $artikel->id }}/300/200" alt="{{ $artikel->judul }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-[11px] font-semibold">
                                                <span class="rounded-full bg-stone-900 px-2 py-0.5 text-white dark:bg-white dark:text-stone-900">{{ $artikel->kategori->nama ?? 'Umum' }}</span>
                                                <span class="font-normal text-stone-400 dark:text-stone-500">{{ $artikel->created_at->translatedFormat('d M Y') }}</span>
                                            </div>
                                            <h3 class="mt-2 line-clamp-2 font-serif text-[15px] font-bold leading-snug group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">{{ $artikel->judul }}</h3>
                                            <p class="mt-1.5 line-clamp-2 text-xs leading-relaxed text-stone-500 dark:text-stone-400">{{ $artikel->ringkasan ?? Str::limit(strip_tags($artikel->konten), 120) }}</p>
                                        </div>
                                        <span class="hidden self-center text-stone-300 transition group-hover:translate-x-0.5 group-hover:text-stone-500 dark:text-stone-600 sm:block">&rarr;</span>
                                    </a>
                                @endforeach
                            </div>

                            @if($artikels->hasPages())
                                <div class="mt-8 flex justify-center">
                                    {{ $artikels->links() }}
                                </div>
                            @endif
                        </div>

                        <aside class="space-y-5">
                            <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
                                <h3 class="font-serif text-sm font-bold">Saring pencarian</h3>
                                <p class="mt-1 text-xs leading-relaxed text-stone-500 dark:text-stone-400">Hasil diurutkan terbaru. Gunakan kata kunci lebih spesifik untuk mempersempit.</p>
                                <div class="mt-4 space-y-2 text-xs">
                                    <a href="{{ route('artikel.index') }}" class="flex items-center justify-between rounded-lg px-3 py-2.5 transition hover:bg-stone-50 dark:hover:bg-white/[0.03]"><span class="font-medium text-stone-700 dark:text-stone-300">Lihat semua artikel</span><span class="text-stone-400">&rarr;</span></a>
                                    <a href="/#kategori" class="flex items-center justify-between rounded-lg px-3 py-2.5 transition hover:bg-stone-50 dark:hover:bg-white/[0.03]"><span class="font-medium text-stone-700 dark:text-stone-300">Telusuri kategori</span><span class="text-stone-400">&rarr;</span></a>
                                </div>
                            </div>
                            <div class="rounded-2xl bg-stone-900 p-6 text-white dark:bg-white/[0.04] dark:ring-1 dark:ring-white/10">
                                <h3 class="font-serif text-base font-bold dark:text-[#e5e5e3]">Tidak menemukan yang dicari?</h3>
                                <p class="mt-2 text-sm leading-relaxed text-white/60 dark:text-stone-400">Coba ejaan berbeda atau kata yang lebih umum. Hubungi redaksi jika butuh bantuan.</p>
                                <a href="{{ route('kontak') }}" class="mt-4 inline-flex rounded-lg bg-white px-4 py-2 text-sm font-semibold text-stone-900 transition hover:bg-stone-100 dark:bg-white dark:text-stone-900">Hubungi kami</a>
                            </div>
                        </aside>
                    </div>
                @else
                    <div class="grid gap-10 py-10 lg:grid-cols-[1fr_300px]">
                        <div class="rounded-2xl border border-dashed border-stone-200 bg-white p-10 text-center dark:border-white/[0.06] dark:bg-[#171716] sm:p-14">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-stone-100 dark:bg-white/[0.06]">
                                <svg class="h-5 w-5 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/><path d="M8 11h6"/></svg>
                            </div>
                            <h3 class="mt-4 font-serif text-lg font-bold">Tidak ada hasil untuk “{{ $query }}”</h3>
                            <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-stone-500 dark:text-stone-400">Periksa ejaan atau coba kata kunci lebih umum. Misalnya “Majapahit” bukan “kerajaan Majapahit kuno”.</p>
                            <div class="mt-6 flex flex-wrap justify-center gap-2">
                                @foreach(['Sejarah Indonesia','Perang Dunia II','Kerajaan Nusantara','Revolusi'] as $s)
                                    <a href="{{ route('search',['q'=>$s]) }}" class="rounded-full bg-stone-900 px-4 py-2 text-xs font-semibold text-white transition hover:bg-stone-800 dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200">{{ $s }}</a>
                                @endforeach
                            </div>
                        </div>
                        <aside class="rounded-2xl border border-stone-200/60 bg-white p-6 dark:border-white/[0.06] dark:bg-[#171716]">
                            <h3 class="font-serif text-sm font-bold">Tips pencarian</h3>
                            <ul class="mt-3 space-y-2 text-sm leading-relaxed text-stone-500 dark:text-stone-400">
                                <li class="flex gap-2"><span class="text-stone-300 dark:text-stone-600">—</span> Minimal 2 karakter, tanpa tanda baca khusus.</li>
                                <li class="flex gap-2"><span class="text-stone-300 dark:text-stone-600">—</span> Gunakan satu kata kunci inti terlebih dahulu.</li>
                                <li class="flex gap-2"><span class="text-stone-300 dark:text-stone-600">—</span> Coba sinonim: “kolonial” / “penjajahan”.</li>
                            </ul>
                        </aside>
                    </div>
                @endif
            @else
                <div class="grid gap-8 py-10 lg:grid-cols-[1fr_340px]">
                    <div class="rounded-2xl border border-dashed border-stone-200 bg-white p-10 text-center dark:border-white/[0.06] dark:bg-[#171716] sm:p-14">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-stone-900 text-white dark:bg-white dark:text-stone-900">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        </div>
                        <h3 class="mt-4 font-serif text-lg font-bold">Mulai pencarian</h3>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-stone-500 dark:text-stone-400">Masukkan kata kunci di atas untuk menelusuri {{ \App\Models\Artikel::published()->count() }} artikel sejarah yang sudah terkurasi.</p>
                        <div class="mt-8 grid gap-3 text-left sm:grid-cols-3">
                            @foreach([
                                ['label'=>'Topik populer','items'=>['Kolonial','Kemerdekaan','Kerajaan']],
                                ['label'=>'Era','items'=>['Orde Baru','Masa Kuno','Reformasi']],
                                ['label'=>'Tokoh','items'=>['Soekarno','Kartini','Diponegoro']],
                            ] as $group)
                                <div class="rounded-xl border border-stone-100 bg-stone-50 p-4 dark:border-white/[0.06] dark:bg-white/[0.03]">
                                    <p class="text-[11px] font-bold uppercase tracking-widest text-stone-400 dark:text-stone-500">{{ $group['label'] }}</p>
                                    <div class="mt-2 flex flex-wrap gap-1.5">
                                        @foreach($group['items'] as $it)
                                            <a href="{{ route('search',['q'=>$it]) }}" class="rounded-full bg-white px-2.5 py-1 text-xs font-medium text-stone-700 ring-1 ring-stone-200 transition hover:bg-stone-900 hover:text-white hover:ring-stone-900 dark:bg-white/5 dark:text-stone-300 dark:ring-white/10 dark:hover:bg-white dark:hover:text-stone-900">{{ $it }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <aside class="space-y-5">
                        <div class="overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
                            <img src="https://picsum.photos/seed/search-empty/600/400" alt="" class="h-40 w-full object-cover" loading="lazy">
                            <div class="p-6">
                                <h3 class="font-serif text-sm font-bold">Jelajahi tanpa kata kunci</h3>
                                <p class="mt-1 text-xs leading-relaxed text-stone-500 dark:text-stone-400">Telusuri kategori atau baca artikel terbaru.</p>
                                <a href="{{ route('artikel.index') }}" class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-stone-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-stone-800 dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200">Lihat semua artikel</a>
                            </div>
                        </div>
                    </aside>
                </div>
            @endif
        </main>

        <footer class="mt-8 border-t border-stone-200/80 dark:border-white/[0.06]">
            <div class="mx-auto flex max-w-[1400px] flex-col items-center justify-between gap-4 px-5 py-8 text-sm text-stone-400 sm:flex-row sm:px-8 dark:text-stone-500">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Look at History') }}.</p>
                <div class="flex items-center gap-5">
                    <a href="{{ route('kontak') }}" class="transition hover:text-stone-600 dark:hover:text-stone-300">Kontak</a>
                    <a href="{{ route('privasi') }}" class="transition hover:text-stone-600 dark:hover:text-stone-300">Kebijakan Privasi</a>
                </div>
            </div>
        </footer>
    </body>
</html>
