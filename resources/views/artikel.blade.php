<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

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
                    <a href="/#tentang" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Tentang</a>
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
                            <a href="{{ url('/dashboard') }}" class="rounded-lg bg-stone-900 px-4 py-2 text-white dark:bg-white dark:text-stone-900">Dashboard</a>
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
                            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-stone-100 font-serif text-sm font-bold text-stone-500 dark:bg-white/[0.05] dark:text-stone-400">
                                {{ substr($artikel->author->name ?? 'A', 0, 1) }}
                            </div>
                            <div class="text-sm">
                                <p class="font-semibold">{{ $artikel->author->name ?? 'Redaksi' }}</p>
                                <p class="text-xs text-stone-400 dark:text-stone-500">Penulis</p>
                            </div>
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
                                <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-stone-100 font-serif text-lg font-bold text-stone-500 dark:bg-white/[0.05] dark:text-stone-400">
                                    {{ substr($artikel->author->name ?? 'A', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold">{{ $artikel->author->name ?? 'Redaksi' }}</p>
                                    <p class="mt-1 text-sm leading-relaxed text-stone-500 dark:text-stone-400">Menulis tentang sejarah dunia untuk pembaca yang ingin memahami masa lalu.</p>
                                    <a href="/" class="mt-3 inline-block text-sm font-semibold text-[#1e3a5f] transition hover:text-[#16304a] dark:text-[#5b9bd5] dark:hover:text-[#7ab3e0]">Lihat semua artikel &rarr;</a>
                                </div>
                            </div>
                        </div>
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
                <p>Belajar Sejarah Dunia</p>
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

            document.addEventListener('DOMContentLoaded', () => {
                initNewsletterForm('newsletter-form');
                initNewsletterForm('newsletter-form-sidebar');
            });
        </script>
    </body>
</html>
