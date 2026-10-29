<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        @include('partials.theme-init')
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Artikel Tersimpan — {{ config('app.name', 'Look at History') }}</title>

        <link rel="icon" href="{{ asset('favicon.ico') }}">

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-[#faf9f7] dark:bg-[#0f0f0e] text-[#171717] dark:text-[#e5e5e3] font-sans antialiased">
        @include('partials.site-header')

        <main class="mx-auto max-w-[1400px] px-5 sm:px-8">
            <section class="pt-12 pb-8 sm:pt-16">
                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-400 dark:text-stone-500">Akun</p>
                <h1 class="mt-3 font-serif text-3xl font-bold leading-[1.1] tracking-tight sm:text-4xl">Artikel Tersimpan</h1>
                <p class="mt-4 max-w-xl text-base leading-relaxed text-stone-500 dark:text-stone-400">
                    Kumpulan artikel yang Anda simpan untuk dibaca nanti.
                </p>
            </section>

            @if ($bookmarks->isEmpty())
                <div class="rounded-2xl border border-dashed border-stone-200 bg-white p-12 text-center dark:border-white/[0.06] dark:bg-[#171716]">
                    <svg class="mx-auto h-12 w-12 text-stone-300 dark:text-stone-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                    <p class="mt-3 text-sm text-stone-500 dark:text-stone-400">Belum ada artikel yang disimpan.</p>
                    <a href="{{ route('artikel.index') }}" class="mt-3 inline-block text-sm font-semibold text-[#1e3a5f] dark:text-[#5b9bd5]">Jelajahi artikel &rarr;</a>
                </div>
            @else
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($bookmarks as $bookmark)
                        @php $artikel = $bookmark->artikel; @endphp
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

                @if ($bookmarks->hasPages())
                    <div class="mt-10 flex justify-center">
                        {{ $bookmarks->links() }}
                    </div>
                @endif
            @endif
        </main>

        @include('partials.site-footer')
    </body>
</html>