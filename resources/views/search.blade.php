<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-adsense-account" content="ca-pub-9007848909516103">

        <title>Hasil Pencarian: {{ $query ?? '' }} — {{ config('app.name', 'Look at History') }}</title>

        @include('partials.seo', [
            'title' => 'Hasil Pencarian: ' . ($query ?? ''),
            'description' => $total > 0
                ? "Ditemukan {$total} artikel untuk \"{$query}\""
                : "Tidak ada hasil untuk \"{$query}\"",
            'url' => route('search', ['q' => $query]),
            'section' => 'Pencarian',
        ])

        <link rel="icon" href="{{ asset('favicon.ico') }}">

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-stone-50 text-stone-900 dark:bg-stone-950 dark:text-stone-100 font-sans antialiased">
        <div class="grain-overlay"></div>
        
        <header class="sticky top-0 z-20 border-b border-stone-200/80 dark:border-white/[0.06] bg-stone-50/80 dark:bg-stone-950/80 backdrop-blur-xl">
            <nav class="mx-auto flex max-w-[1400px] items-center justify-between px-5 py-4 sm:px-8">
                <a href="/" class="flex items-center gap-3">
                    <img src="{{ asset('logo_LAH.jpg') }}" alt="{{ config('app.name', 'Look at History') }}" class="h-9 w-9 rounded-full object-cover ring-1 ring-stone-200 dark:ring-white/10">
                    <span class="font-serif text-lg font-bold tracking-tight">Look at History</span>
                </a>
                <div class="hidden items-center gap-1 text-sm font-medium sm:flex">
                    <a href="/" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Beranda</a>
                    <a href="/#artikel" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Artikel</a>
                    <a href="/#kategori" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Kategori</a>
                    <a href="{{ route('tentang') }}" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Tentang</a>
                </div>
                <div class="hidden sm:flex items-center gap-2">
                    <form action="{{ route('search') }}" method="GET" class="relative">
                        <input
                            type="text"
                            name="q"
                            placeholder="Cari..."
                            class="w-48 sm:w-64 rounded-lg border border-stone-200 bg-white px-3.5 py-1.5 pl-9 text-sm placeholder:text-stone-400 focus:border-[var(--color-accent)] focus:outline-none focus:ring-1 focus:ring-[var(--color-accent)] dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-[var(--color-accent-light)] dark:focus:ring-[var(--color-accent-light)]"
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
                        @endif
                    @endif
                </div>
            </nav>
        </header>

        <main class="mx-auto max-w-[1400px] px-5 sm:px-8">
            {{-- Search Hero --}}
            <section class="pt-14 pb-10 sm:pt-20 animate-in">
                <a href="/" class="inline-flex items-center gap-1.5 text-sm font-semibold text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                    Kembali ke Beranda
                </a>

                <div class="mt-10 max-w-3xl">
                    <form method="GET" action="{{ route('search') }}" class="flex gap-3">
                        <input
                            type="text"
                            name="q"
                            value="{{ $query ?? '' }}"
                            placeholder="Cari artikel sejarah..."
                            autofocus
                            class="flex-1 rounded-xl border border-stone-200 bg-white px-4 py-4 text-base placeholder:text-stone-400 focus:border-[var(--color-accent)] focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]/10 dark:border-white/10 dark:bg-stone-950 dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-[var(--color-accent-light)] dark:focus:ring-[var(--color-accent-light)]/10"
                        >
                        <button type="submit" class="btn-primary whitespace-nowrap">Cari</button>
                    </form>

                    @if ($query)
                        <p class="mt-5 text-sm text-stone-500 dark:text-stone-400">
                            {{ $total > 0
                                ? "Ditemukan <strong>{$total}</strong> hasil untuk \"<strong>{$query}</strong>\""
                                : "Tidak ada hasil untuk \"<strong>{$query}</strong>\""
                            }}
                        </p>
                    @endif
                </div>
            </section>

            {{-- Results --}}
            @if ($query)
                @if ($artikels->isNotEmpty())
                    <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($artikels as $index => $artikel)
                            <a href="{{ route('artikel.show', $artikel) }}" class="card group overflow-hidden animate-in" style="animation-delay: {{ $index * 80 }}ms;">
                                <div class="relative overflow-hidden bg-stone-100 dark:bg-stone-900/50">
                                    @if ($artikel->gambar)
                                        <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}" class="aspect-[4/3] w-full object-cover transition-all duration-700 group-hover:scale-105" loading="lazy">
                                    @else
                                        <img src="https://picsum.photos/seed/search-{{ $artikel->id }}/600/450" alt="{{ $artikel->judul }}" class="aspect-[4/3] w-full object-cover transition-all duration-700 group-hover:scale-105" loading="lazy">
                                    @endif
                                </div>
                                <div class="p-6">
                                    <div class="flex items-center gap-2 text-xs font-semibold">
                                        <span class="text-[var(--color-accent)] dark:text-[var(--color-accent-light)]">{{ $artikel->kategori->nama ?? 'Umum' }}</span>
                                        <span class="text-stone-300 dark:text-stone-600">&middot;</span>
                                        <span class="text-stone-400 dark:text-stone-500">{{ $artikel->created_at->format('d M Y') }}</span>
                                    </div>
                                    <h3 class="mt-3 font-serif text-lg font-bold leading-snug group-hover:text-[var(--color-accent)] dark:group-hover:text-[var(--color-accent-light)] transition-colors">{{ $artikel->judul }}</h3>
                                    <p class="mt-2 text-sm leading-relaxed text-stone-500 dark:text-stone-400">{{ $artikel->ringkasan ?? Str::limit(strip_tags($artikel->konten), 140) }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if ($artikels->hasPages())
                        <div class="mt-12 flex justify-center">
                            {{ $artikels->links() }}
                        </div>
                    @endif
                @else
                    <div class="mt-16 card p-16 text-center animate-in">
                        <svg class="mx-auto h-14 w-14 text-stone-300 dark:text-stone-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        <p class="mt-4 text-sm text-stone-500 dark:text-stone-400">Tidak ada artikel yang cocok dengan pencarian Anda.</p>
                        <p class="mt-1 text-xs text-stone-400 dark:text-stone-500">Coba kata kunci lain atau periksa ejaan.</p>
                    </div>
                @endif
            @else
                <div class="mt-16 card p-16 text-center animate-in">
                    <svg class="mx-auto h-14 w-14 text-stone-300 dark:text-stone-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <p class="mt-4 text-sm text-stone-500 dark:text-stone-400">Masukkan kata kunci untuk mencari artikel.</p>
                </div>
            @endif
        </main>

        <footer class="mt-20 border-t border-stone-200/80 dark:border-white/[0.06]">
            <div class="mx-auto flex max-w-[1400px] flex-col items-center justify-between gap-4 px-5 py-10 text-sm text-stone-400 sm:flex-row sm:px-8 dark:text-stone-500">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Look at History') }}.</p>
                <div class="flex items-center gap-5">
                    <a href="{{ route('kontak') }}" class="transition hover:text-stone-600 dark:hover:text-stone-300">Kontak</a>
                    <a href="{{ route('privasi') }}" class="transition hover:text-stone-600 dark:hover:text-stone-300">Kebijakan Privasi</a>
                </div>
            </div>
        </footer>
    </body>
</html>