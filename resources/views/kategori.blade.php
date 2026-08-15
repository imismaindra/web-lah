<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-adsense-account" content="ca-pub-9007848909516103">

        <title>{{ $kategori->nama }} — {{ config('app.name', 'Look at History') }}</title>

        @include('partials.seo', [
            'title' => $kategori->nama,
            'description' => $kategori->deskripsi ?? "Artikel sejarah kategori {$kategori->nama}. Jelajahi topik-topik menarik seputar {$kategori->nama}.",
            'image' => $kategori->gambar ?? asset('logo_LAH.jpg'),
            'url' => route('kategori.show', $kategori),
            'section' => 'Kategori',
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
                    <a href="/#artikel" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Artikel</a>
                    <a href="/#kategori" class="rounded-lg bg-stone-900 px-3 py-1.5 text-white dark:bg-white dark:text-stone-900">Kategori</a>
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

        <main class="mx-auto max-w-[1400px] px-5 sm:px-8">
            {{-- Hero --}}
            <section class="pt-12 pb-8 sm:pt-16">
                <a href="/" class="inline-flex items-center gap-1.5 text-sm font-semibold text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                    Kembali
                </a>
                <p class="mt-8 text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-400 dark:text-stone-500">Kategori</p>
                <h1 class="mt-3 font-serif text-3xl font-bold leading-[1.1] tracking-tight sm:text-4xl lg:text-5xl">{{ $kategori->nama }}</h1>
                @if ($kategori->deskripsi)
                    <p class="mt-4 max-w-xl text-base leading-relaxed text-stone-500 dark:text-stone-400">{{ $kategori->deskripsi }}</p>
                @endif
            </section>

            {{-- Articles --}}
            @if ($artikels->isEmpty())
                <div class="rounded-2xl border border-dashed border-stone-200 bg-white p-12 text-center dark:border-white/[0.06] dark:bg-[#171716]">
                    <svg class="mx-auto h-12 w-12 text-stone-300 dark:text-stone-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                    <p class="mt-3 text-sm text-stone-500 dark:text-stone-400">Belum ada artikel di kategori ini.</p>
                </div>
            @else
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($artikels as $artikel)
                        <a href="{{ route('artikel.show', $artikel) }}" class="group overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
                            <div class="relative overflow-hidden bg-stone-100 dark:bg-stone-800/50">
                                @if ($artikel->gambar)
                                    <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}" class="h-44 w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                                @else
                                    <img src="https://picsum.photos/seed/kategori-{{ $artikel->id }}/600/400" alt="{{ $artikel->judul }}" class="h-44 w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                                @endif
                            </div>
                            <div class="p-5">
                                <div class="flex items-center gap-2 text-xs font-semibold">
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
                    @endforeach
                </div>

                @if ($artikels->hasPages())
                    <div class="mt-10 flex justify-center">
                        {{ $artikels->links() }}
                    </div>
                @endif
            @endif
        </main>

        <footer class="mt-16 border-t border-stone-200/80 dark:border-white/[0.06]">
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