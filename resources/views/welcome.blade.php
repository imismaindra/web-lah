<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-adsense-account" content="ca-pub-9007848909516103">

        <title>{{ config('app.name', 'Look at History') }}</title>

        @include('partials.seo', [
            'title' => config('app.name', 'Look at History'),
            'description' => 'Blog sejarah dunia ringkas & terpercaya. Temukan artikel peradaban kuno, perang dunia, tokoh sejarah, dan peristiwa penting masa lalu.',
            'url' => url('/'),
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
                    <a href="/" class="rounded-lg bg-stone-900 px-3 py-1.5 text-white dark:bg-white dark:text-stone-900">Beranda</a>
                    <a href="/artikel" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Artikel</a>
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

        <main class="mx-auto max-w-[1400px] px-5 sm:px-8">
            <section class="pt-10 pb-2 sm:pt-14">
                @php
                    $spotlight = $featuredArtikel ?? $latestArtikels->first();
                    $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][now()->month - 1];
                    $edisi = $bulan . ' ' . now()->year;
                @endphp

                <div class="hero-rise flex flex-wrap items-center justify-between gap-3 border-b border-stone-200/80 pb-4 dark:border-white/[0.06]">
                    <p class="flex items-center gap-3 text-[11px] font-bold uppercase tracking-[0.18em] text-stone-400 dark:text-stone-500">
                        <span class="h-px w-10 bg-stone-300 dark:bg-white/15"></span>
                        Blog Sejarah Dunia
                    </p>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-400 dark:text-stone-500">{{ $edisi }}</p>
                </div>

                <div class="mt-8 grid gap-12 lg:grid-cols-[1.15fr_0.85fr] lg:gap-14">
                    <article class="hero-rise group relative overflow-hidden rounded-2xl border border-stone-200/60 bg-stone-100 dark:border-white/[0.06] dark:bg-stone-800/50" style="animation-delay: 140ms">
                        @if ($spotlight)
                            <a href="{{ route('artikel.show', $spotlight) }}" class="block">
                                <img src="{{ $spotlight->gambar ? asset('storage/' . $spotlight->gambar) : 'https://picsum.photos/seed/hero-' . $spotlight->id . '/1200/800' }}" alt="{{ $spotlight->judul }}" class="aspect-[4/3] w-full object-cover transition duration-700 group-hover:scale-[1.03] sm:aspect-[16/9] lg:aspect-[16/10]" loading="eager">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0f0f0e]/85 via-[#0f0f0e]/20 to-transparent"></div>
                                <div class="absolute inset-x-0 bottom-0 p-6 sm:p-8">
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1.5">
                                        <span class="rounded-full bg-[#1e3a5f] px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-white dark:bg-[#5b9bd5] dark:text-[#0f0f0e]">{{ $spotlight->kategori->nama ?? 'Umum' }}</span>
                                        <span class="text-[11px] font-semibold text-white/70">{{ $spotlight->created_at->format('d M Y') }}</span>
                                    </div>
                                    <h1 class="mt-4 line-clamp-2 font-serif text-2xl font-bold leading-tight tracking-tight text-white sm:text-3xl lg:text-4xl">{{ $spotlight->judul }}</h1>
                                    <p class="mt-3 line-clamp-2 max-w-xl text-sm leading-relaxed text-white/70">{{ $spotlight->ringkasan ?? Str::limit(strip_tags($spotlight->konten), 140) }}</p>
                                    <p class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-white">
                                        Baca selengkapnya
                                        <span class="transition-transform duration-300 group-hover:translate-x-1">&rarr;</span>
                                    </p>
                                </div>
                            </a>
                            <span class="absolute top-4 left-4 rounded-sm bg-[#0f0f0e]/55 px-2.5 py-1 font-mono text-[10px] font-bold tracking-widest text-white/85 backdrop-blur-sm">UNGGULAN</span>
                        @else
                            <div class="flex aspect-[16/9] items-center justify-center">
                                <div class="text-center">
                                    <p class="font-serif text-lg font-bold text-stone-500 dark:text-stone-400">Belum ada artikel</p>
                                    <p class="mt-1 text-sm text-stone-400 dark:text-stone-500">Mulai publikasikan artikel sejarah pertamamu.</p>
                                </div>
                            </div>
                        @endif
                    </article>

                    <aside class="flex flex-col">
                        <div class="hero-rise flex items-baseline justify-between border-b border-stone-200/80 pb-4 dark:border-white/[0.06]" style="animation-delay: 220ms">
                            <h2 class="font-serif text-lg font-bold tracking-tight">Populer</h2>
                            <span class="text-[10px] font-bold uppercase tracking-[0.18em] text-stone-400 dark:text-stone-500">Paling dibaca</span>
                        </div>
                        <ol class="mt-1 divide-y divide-stone-200/70 dark:divide-white/[0.06]">
                            @forelse ($popularArtikels->take(4) as $index => $artikel)
                                <li class="hero-rise" style="animation-delay: {{ 300 + $index * 80 }}ms">
                                    <a href="{{ route('artikel.show', $artikel) }}" class="group flex items-center gap-4 py-3.5">
                                        <span class="w-8 shrink-0 font-serif text-2xl font-bold text-stone-200 transition group-hover:text-[#1e3a5f] dark:text-white/10 dark:group-hover:text-[#5b9bd5]">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                        <div class="min-w-0 flex-1">
                                            <p class="line-clamp-2 font-serif text-[15px] font-bold leading-snug text-stone-800 transition group-hover:text-[#1e3a5f] dark:text-stone-200 dark:group-hover:text-[#5b9bd5]">{{ $artikel->judul }}</p>
                                            <p class="mt-1 text-[11px] font-semibold text-stone-400 dark:text-stone-500">{{ number_format($artikel->views) }} dibaca</p>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li class="py-8 text-center text-sm text-stone-400 dark:text-stone-500">Belum ada data</li>
                            @endforelse
                        </ol>
                        <a href="#artikel" class="hero-rise group mt-3 inline-flex items-center gap-2 text-sm font-semibold text-[#1e3a5f] dark:text-[#5b9bd5]" style="animation-delay: 640ms">
                            Semua artikel
                            <span class="transition-transform duration-300 group-hover:translate-x-0.5">&rarr;</span>
                        </a>
                    </aside>
                </div>
            </section>

            <section id="perjalanan-waktu" class="scroll-mt-24 py-8 sm:py-10">
                <h2 class="font-serif text-xl font-bold tracking-tight">Perjalanan Waktu</h2>
                <div class="mt-5 flex gap-4 overflow-x-auto pb-4 scrollbar-hide">
                    @forelse ($eras as $era)
                        <a href="{{ route('era.show', $era) }}" class="group relative h-64 w-56 flex-shrink-0 overflow-hidden rounded-2xl sm:w-64">
                            @if ($era->gambar)
                                <img src="{{ asset('storage/' . $era->gambar) }}" alt="{{ $era->nama }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-110" loading="lazy">
                            @else
                                <img src="https://picsum.photos/seed/era-{{ $era->slug }}/400/500" alt="{{ $era->nama }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-110" loading="lazy">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 p-5">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-white/60">{{ $era->periode }}</p>
                                <h3 class="mt-1 font-serif text-lg font-bold text-white">{{ $era->nama }}</h3>
                                <p class="mt-1 text-[11px] font-semibold text-white/50">{{ $era->artikel_count }} artikel</p>
                            </div>
                        </a>
                    @empty
                        <div class="flex h-64 w-full flex-shrink-0 items-center justify-center rounded-2xl border border-dashed border-stone-200 text-sm text-stone-400 dark:border-white/[0.06] dark:text-stone-500">Belum ada era</div>
                    @endforelse
                </div>
            </section>

            <div class="grid gap-8 lg:grid-cols-[1fr_320px]">
                <div>
                    <div id="artikel" class="scroll-mt-24">
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
                        <div id="kategori" class="mb-4 scroll-mt-24">
                            <h2 class="font-serif text-base font-bold">Kategori</h2>
                        </div>
                        <ul class="space-y-0.5 text-sm">
                            @forelse ($kategoris as $kategori)
                                <li>
                                    <a href="{{ route('kategori.show', $kategori) }}" class="flex items-center justify-between rounded-lg px-3 py-2 transition hover:bg-stone-50 dark:hover:bg-white/[0.03]">
                                        <span class="text-stone-600 dark:text-stone-300">{{ $kategori->nama }}</span>
                                        <span class="text-xs font-semibold text-stone-400 dark:text-stone-500">{{ $kategori->artikel_count }}</span>
                                    </a>
                                </li>
                            @empty
                                <li class="px-3 py-2 text-xs text-stone-400 dark:text-stone-500">Belum ada kategori</li>
                            @endforelse
                        </ul>
                    </div>

                    <div id="tentang" class="scroll-mt-24 rounded-2xl border border-stone-200/60 bg-white p-6 dark:border-white/[0.06] dark:bg-[#171716]">
                        <h2 class="font-serif text-base font-bold">Tentang Blog Ini</h2>
                        <p class="mt-2 text-sm leading-relaxed text-stone-500 dark:text-stone-400">
                            Look at History menyajikan artikel sejarah dunia secara ringkas dan terpercaya,
                            dari peradaban kuno hingga konflik modern.
                        </p>
                        <a href="{{ route('tentang') }}" class="mt-4 inline-block text-sm font-semibold text-[#1e3a5f] transition hover:text-[#16304a] dark:text-[#5b9bd5] dark:hover:text-[#7ab3e0]">Tentang kami selengkapnya &rarr;</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="mt-4 inline-block rounded-lg bg-stone-900 px-4 py-2 text-xs font-semibold text-white transition hover:bg-stone-800 dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200">
                                Mulai Belajar
                            </a>
                        @endif
                    </div>
                </aside>
            </div>

            <section class="py-10 sm:py-14">
                <div class="flex items-baseline justify-between gap-4">
                    <h2 class="font-serif text-xl font-bold tracking-tight sm:text-2xl">Jelajahi Topik</h2>
                    <p class="text-xs font-semibold text-stone-400 dark:text-stone-500">{{ $topiks->count() }} topik sejarah</p>
                </div>
                <div class="mt-6 grid gap-x-10 sm:grid-cols-2">
                    @php $n = 0; @endphp
                    @forelse ($topiks->chunk(8) as $column)
                        <ul>
                            @foreach ($column as $topik)
                                @php $n++; @endphp
                                <li>
                                    <a href="{{ route('topik.show', $topik) }}" class="group flex items-baseline justify-between gap-4 border-b border-stone-200/70 py-3.5 transition hover:border-[#1e3a5f]/40 dark:border-white/[0.06] dark:hover:border-[#5b9bd5]/40">
                                        <span class="flex min-w-0 items-baseline gap-3">
                                            <span class="shrink-0 font-serif text-sm font-bold text-stone-300 dark:text-stone-600">{{ str_pad($n, 2, '0', STR_PAD_LEFT) }}</span>
                                            <span class="truncate font-serif text-[15px] font-bold leading-snug text-stone-800 transition group-hover:text-[#1e3a5f] dark:text-stone-200 dark:group-hover:text-[#5b9bd5]">{{ $topik->nama }}</span>
                                        </span>
                                        <span class="shrink-0 text-xs font-semibold text-stone-400 transition group-hover:text-[#1e3a5f] dark:text-stone-500 dark:group-hover:text-[#5b9bd5]">{{ $topik->artikel_count }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @empty
                        <div class="col-span-2 flex h-40 items-center justify-center rounded-2xl border border-dashed border-stone-200 text-sm text-stone-400 dark:border-white/[0.06] dark:text-stone-500">Belum ada topik</div>
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
                    <form id="newsletter-form" method="POST" action="{{ route('newsletter.subscribe') }}" class="mt-6 flex flex-col gap-3 sm:flex-row">
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
                <div class="flex items-center gap-5">
                    <a href="{{ route('kontak') }}" class="transition hover:text-stone-600 dark:hover:text-stone-300">Kontak</a>
                    <a href="{{ route('privasi') }}" class="transition hover:text-stone-600 dark:hover:text-stone-300">Kebijakan Privasi</a>
                </div>
            </div>
        </footer>

        <script>
            document.querySelectorAll('a[href="/#kategori"]').forEach((link) => {
                link.addEventListener('click', (e) => {
                    const target = document.getElementById('kategori');
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });

            document.getElementById('newsletter-form')?.addEventListener('submit', async (e) => {
                e.preventDefault();
                const form = e.target;
                const btn = form.querySelector('button[type="submit"]');
                const originalBtnText = btn.textContent;
                btn.disabled = true;
                btn.textContent = 'Mendaftar...';

                try {
                    const formData = new FormData(form);
                    const res = await fetch(form.action, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value },
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
        </script>
    </body>
</html>