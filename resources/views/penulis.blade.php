<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        @include('partials.theme-init')
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-adsense-account" content="ca-pub-9007848909516103">

        <title>{{ $penulis->nama }} — {{ config('app.name', 'Look at History') }}</title>

        @include('partials.seo', [
            'title' => $penulis->nama,
            'description' => $penulis->bio ?? 'Halaman profil penulis ' . $penulis->nama . ' di ' . config('app.name', 'Look at History'),
            'image' => $penulis->avatar ? asset('storage/' . $penulis->avatar) : asset('logo_LAH.jpg'),
            'url' => route('penulis.show', $penulis),
            'section' => 'Penulis',
        ])

        <link rel="icon" href="{{ asset('favicon.ico') }}">

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-[#faf9f7] dark:bg-[#0f0f0e] text-[#171717] dark:text-[#e5e5e3] font-sans antialiased">
        @include('partials.site-header')

        <main id="main" class="mx-auto max-w-[1400px] px-5 sm:px-8">
            {{-- Profile --}}
            <section class="pt-12 pb-8 sm:pt-16">
                <a href="/" class="inline-flex items-center gap-1.5 text-sm font-semibold text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                    Kembali
                </a>
                <div class="mt-8 flex items-start gap-5">
                    @if ($penulis->avatar)
                        <img src="{{ asset('storage/' . $penulis->avatar) }}" alt="{{ $penulis->nama }}" class="h-20 w-20 flex-shrink-0 rounded-full object-cover ring-1 ring-stone-200 dark:ring-white/10 sm:h-24 sm:w-24">
                    @else
                        <div class="flex h-20 w-20 flex-shrink-0 items-center justify-center rounded-full bg-stone-100 font-serif text-2xl font-bold text-stone-500 dark:bg-white/[0.05] dark:text-stone-400 sm:h-24 sm:w-24">
                            {{ substr($penulis->nama, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-400 dark:text-stone-500">Penulis</p>
                        <h1 class="mt-2 font-serif text-3xl font-bold leading-[1.1] tracking-tight sm:text-4xl lg:text-5xl">{{ $penulis->nama }}</h1>
                        <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-stone-400 dark:text-stone-500">
                            <span>{{ $penulis->artikel_count }} artikel</span>
                            @if ($penulis->website)
                                <a href="{{ $penulis->website }}" target="_blank" rel="noopener" class="font-semibold text-[#1e3a5f] transition hover:text-[#16304a] dark:text-[#5b9bd5] dark:hover:text-[#7ab3e0]">Website &nearr;</a>
                            @endif
                        </div>
                        @if ($penulis->bio)
                            <p class="mt-4 max-w-xl text-base leading-relaxed text-stone-500 dark:text-stone-400">{{ $penulis->bio }}</p>
                        @endif
                    </div>
                </div>
            </section>

            {{-- Articles --}}
            @if ($artikels->isEmpty())
                <div class="rounded-2xl border border-dashed border-stone-200 bg-white p-12 text-center dark:border-white/[0.06] dark:bg-[#171716]">
                    <svg class="mx-auto h-12 w-12 text-stone-300 dark:text-stone-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                    <p class="mt-3 text-sm text-stone-500 dark:text-stone-400">Belum ada artikel dari penulis ini.</p>
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