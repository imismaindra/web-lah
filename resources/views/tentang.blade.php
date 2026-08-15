<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Tentang — {{ config('app.name', 'Look at History') }}</title>

        @include('partials.seo', [
            'title' => 'Tentang — ' . config('app.name', 'Look at History'),
            'description' => 'Kenali Look at History, blog sejarah dunia yang menyajikan peristiwa, tokoh, dan peradaban masa lalu secara ringkas, akurat, dan mudah dipahami.',
            'url' => route('tentang'),
        ])

        <link rel="icon" href="{{ asset('favicon.ico') }}">

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
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

            @keyframes reveal-up {
                from {
                    opacity: 0;
                    transform: translateY(28px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @supports (animation-timeline: view()) {
                .reveal {
                    animation: reveal-up 0.9s cubic-bezier(0.16, 1, 0.3, 1) both;
                    animation-timeline: view();
                    animation-range: entry 0% entry 45%;
                }
            }

            @media (prefers-reduced-motion: reduce) {
                .reveal {
                    animation: none !important;
                    opacity: 1;
                }
            }
        </style>
    </head>
    <body class="bg-[#faf9f7] dark:bg-[#0f0f0e] text-[#171717] dark:text-[#e5e5e3] font-sans antialiased">
        <div class="grain-overlay"></div>

        <header class="sticky top-0 z-20 border-b border-stone-200/80 dark:border-white/[0.06] bg-[#faf9f7]/80 dark:bg-[#0f0f0e]/80 backdrop-blur-xl">
            <nav class="mx-auto flex max-w-[1400px] items-center justify-between px-5 py-4 sm:px-8">
                <a href="/" class="flex items-center gap-3">
                    <img src="{{ asset('logo_LAH.jpg') }}" alt="{{ config('app.name', 'Look at History') }}" class="h-9 w-9 rounded-full object-cover ring-1 ring-stone-200 dark:ring-white/10">
                    <span class="font-serif text-lg font-bold tracking-tight">Look at History</span>
                </a>
                <div class="hidden items-center gap-1 text-sm font-medium sm:flex">
                    <a href="/" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Beranda</a>
                    <a href="/#artikel" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Artikel</a>
                    <a href="/#kategori" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Kategori</a>
                    <a href="{{ route('tentang') }}" class="rounded-lg bg-stone-900 px-3 py-1.5 text-white dark:bg-white dark:text-stone-900">Tentang</a>
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

        <main class="mx-auto max-w-[1400px] px-5 sm:px-8">
            {{-- Hero --}}
            <section class="pt-12 pb-16 sm:pt-16 sm:pb-20">
                <div class="grid gap-12 lg:grid-cols-[1fr_0.75fr] lg:items-center lg:gap-20">
                    <div>
                        <p class="hero-rise flex items-center gap-3 text-[11px] font-bold uppercase tracking-[0.18em] text-stone-400 dark:text-stone-500">
                            <span class="h-px w-10 bg-stone-300 dark:bg-white/15"></span>
                            Tentang Redaksi
                        </p>

                        <h1 class="hero-rise mt-6 font-serif text-4xl font-bold leading-[1.08] tracking-tight sm:text-5xl lg:text-6xl" style="animation-delay: 100ms">
                            Membaca masa lalu, memahami hari ini.
                        </h1>

                        <p class="hero-rise mt-6 max-w-xl text-base leading-relaxed text-stone-500 dark:text-stone-400" style="animation-delay: 200ms">
                            Look at History menyajikan peristiwa, tokoh, dan peradaban masa lalu secara ringkas, akurat, dan mudah dipahami.
                        </p>

                        <div class="hero-rise mt-9 flex flex-wrap items-center gap-3" style="animation-delay: 300ms">
                            <a href="/#artikel" class="rounded-lg bg-stone-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-stone-800 dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200">Jelajahi Artikel</a>
                            <a href="#berlangganan" class="rounded-lg border border-stone-300 px-6 py-3 text-sm font-semibold text-stone-700 transition hover:border-stone-900 hover:text-stone-900 dark:border-white/15 dark:text-stone-300 dark:hover:border-white dark:hover:text-white">Berlangganan</a>
                        </div>
                    </div>

                    <figure class="hero-rise relative overflow-hidden rounded-2xl" style="animation-delay: 180ms">
                        <img src="https://picsum.photos/seed/lah-tentang-hero/900/1100" alt="Suasana pembacaan sejarah" class="aspect-[4/5] w-full object-cover" loading="eager">
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#0f0f0e]/75 to-transparent p-6">
                            <figcaption class="font-serif text-sm italic text-white/85">Setiap masa punya cerita yang menunggu untuk dibaca.</figcaption>
                        </div>
                    </figure>
                </div>
            </section>

            {{-- Manifesto --}}
            <section class="reveal border-t border-stone-200/80 py-20 sm:py-28 dark:border-white/[0.06]">
                <blockquote class="mx-auto max-w-3xl text-center">
                    <p class="font-serif text-2xl font-bold leading-snug tracking-tight sm:text-3xl lg:text-4xl">
                        Kami percaya sejarah bukan milik akademisi semata. Ia milik siapa pun yang ingin memahami dari mana kita datang.
                    </p>
                    <footer class="mt-10 text-xs font-bold uppercase tracking-[0.18em] text-stone-400 dark:text-stone-500">Redaksi Look at History</footer>
                </blockquote>
            </section>

            {{-- Asal-usul Instagram --}}
            <section class="reveal border-t border-stone-200/80 py-16 sm:py-20 dark:border-white/[0.06]">
                <div class="grid gap-10 lg:grid-cols-[1.15fr_0.85fr] lg:items-center lg:gap-16">
                    <div>
                        <h2 class="font-serif text-2xl font-bold tracking-tight sm:text-3xl">Berawal dari Instagram</h2>
                        <p class="mt-5 max-w-xl text-sm leading-relaxed text-stone-500 dark:text-stone-400 sm:text-base">
                            Look at History lahir dari akun Instagram
                            <a href="https://www.instagram.com/lookathistory/" target="_blank" rel="noopener noreferrer" class="font-semibold text-[#1e3a5f] underline underline-offset-2 dark:text-[#5b9bd5]">@lookathistory</a>.
                            Cerita sejarah kami mulai dari unggahan pendek yang mudah dibaca di sela waktu luang.
                            Dari satu layar kecil itulah tulisan tumbuh menjadi artikel panjang di blog ini.
                        </p>
                        <p class="mt-4 max-w-xl text-sm leading-relaxed text-stone-500 dark:text-stone-400 sm:text-base">
                            Dua ruang, satu misi: membawa masa lalu mendekat ke pembaca.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-stone-200/60 bg-white p-7 sm:p-8 dark:border-white/[0.06] dark:bg-[#171716]">
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-[#feda75] via-[#d62976] to-[#962fbf]">
                                <img src="https://cdn.simpleicons.org/instagram/ffffff" alt="Instagram" class="h-7 w-7">
                            </div>
                            <div>
                                <p class="font-serif text-lg font-bold tracking-tight">@lookathistory</p>
                                <p class="mt-0.5 text-xs font-semibold text-stone-400 dark:text-stone-500">Serpihan sejarah, tiap hari</p>
                            </div>
                        </div>
                        <p class="mt-6 text-sm leading-relaxed text-stone-500 dark:text-stone-400">Ikuti kami di Instagram untuk kisah singkat, gambar peninggalan, dan cuplikan artikel terbaru.</p>
                        <a href="https://www.instagram.com/lookathistory/" target="_blank" rel="noopener noreferrer" class="mt-6 inline-flex items-center gap-2.5 rounded-lg bg-[#1e3a5f] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16304a] dark:bg-[#5b9bd5] dark:text-[#0f0f0e] dark:hover:bg-[#7ab3e0]">
                            <img src="https://cdn.simpleicons.org/instagram/ffffff" alt="" class="h-4 w-4 dark:invert"> Ikuti @lookathistory
                        </a>
                    </div>
                </div>
            </section>

            {{-- Cara Kami Menulis --}}
            <section class="reveal py-16 sm:py-20">
                <div class="max-w-2xl">
                    <h2 class="font-serif text-2xl font-bold tracking-tight sm:text-3xl">Cara Kami Menulis</h2>
                    <p class="mt-3 text-sm leading-relaxed text-stone-500 dark:text-stone-400">Di balik setiap artikel ada proses yang tidak pernah kami lewatkan.</p>
                </div>

                <div class="mt-10 divide-y divide-stone-200/70 dark:divide-white/[0.06]">
                    <div class="group grid gap-2 py-6 sm:grid-cols-[72px_1fr_2fr] sm:items-baseline sm:gap-8">
                        <span class="font-serif text-2xl font-bold text-stone-200 transition group-hover:text-[#1e3a5f] dark:text-white/10 dark:group-hover:text-[#5b9bd5]">01</span>
                        <h3 class="font-serif text-lg font-bold tracking-tight">Riset</h3>
                        <p class="text-sm leading-relaxed text-stone-500 dark:text-stone-400">Setiap kisah dimulai dari sumber. Kami menelusuri pustaka, arsip, dan catatan sejarah sebelum menulis satu kalimat pun.</p>
                    </div>
                    <div class="group grid gap-2 py-6 sm:grid-cols-[72px_1fr_2fr] sm:items-baseline sm:gap-8">
                        <span class="font-serif text-2xl font-bold text-stone-200 transition group-hover:text-[#1e3a5f] dark:text-white/10 dark:group-hover:text-[#5b9bd5]">02</span>
                        <h3 class="font-serif text-lg font-bold tracking-tight">Penulisan</h3>
                        <p class="text-sm leading-relaxed text-stone-500 dark:text-stone-400">Cerita disusun ringkas dan kronologis. Panjang pas, tanpa menggelembung, tanpa jargon yang menghalangi pembaca.</p>
                    </div>
                    <div class="group grid gap-2 py-6 sm:grid-cols-[72px_1fr_2fr] sm:items-baseline sm:gap-8">
                        <span class="font-serif text-2xl font-bold text-stone-200 transition group-hover:text-[#1e3a5f] dark:text-white/10 dark:group-hover:text-[#5b9bd5]">03</span>
                        <h3 class="font-serif text-lg font-bold tracking-tight">Pemeriksaan Fakta</h3>
                        <p class="text-sm leading-relaxed text-stone-500 dark:text-stone-400">Nama, tanggal, dan urutan peristiwa diperiksa ulang. Klaim yang tidak bisa dipertanggungjawabkan tidak pernah tayang.</p>
                    </div>
                    <div class="group grid gap-2 py-6 sm:grid-cols-[72px_1fr_2fr] sm:items-baseline sm:gap-8">
                        <span class="font-serif text-2xl font-bold text-stone-200 transition group-hover:text-[#1e3a5f] dark:text-white/10 dark:group-hover:text-[#5b9bd5]">04</span>
                        <h3 class="font-serif text-lg font-bold tracking-tight">Publikasi</h3>
                        <p class="text-sm leading-relaxed text-stone-500 dark:text-stone-400">Artikel terbit dalam bahasa yang terbaca siapa pun, dari pelajar hingga penikmat sejarah.</p>
                    </div>
                </div>
            </section>

            {{-- Prinsip --}}
            <section class="reveal py-16 sm:py-20">
                <div class="flex items-baseline justify-between gap-4">
                    <h2 class="font-serif text-2xl font-bold tracking-tight sm:text-3xl">Prinsip Kami</h2>
                    <p class="shrink-0 text-xs font-semibold text-stone-400 dark:text-stone-500">Tiga prinsip yang kami pegang</p>
                </div>

                <div class="mt-8 grid gap-5 md:grid-cols-6">
                    <div class="relative overflow-hidden rounded-2xl bg-[#1e3a5f] p-8 sm:p-10 md:col-span-4 dark:bg-[#5b9bd5]">
                        <p class="font-serif text-2xl font-bold leading-snug tracking-tight text-white sm:text-3xl dark:text-[#0f0f0e]">
                            Sejarah yang jujur tidak butuh sensasi. Ia cukup kuat berdiri sendiri.
                        </p>
                        <p class="mt-6 text-[11px] font-bold uppercase tracking-[0.18em] text-white/60 dark:text-[#0f0f0e]/60">Kejujuran</p>
                    </div>

                    <div class="relative min-h-[260px] overflow-hidden rounded-2xl md:col-span-2">
                        <img src="https://picsum.photos/seed/lah-tentang-stone/800/1000" alt="Tekstur batu peninggalan masa lampau" class="absolute inset-0 h-full w-full object-cover" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                        <p class="absolute bottom-4 left-5 right-5 font-serif text-sm italic text-white/85">Dari masa lalu, kita belajar hari ini.</p>
                    </div>

                    <div class="rounded-2xl border border-stone-200/60 bg-stone-50 p-8 sm:p-10 md:col-span-2 dark:border-white/[0.06] dark:bg-white/[0.03]">
                        <h3 class="font-serif text-xl font-bold tracking-tight">Ringkas</h3>
                        <p class="mt-3 text-sm leading-relaxed text-stone-500 dark:text-stone-400">Satu artikel, satu kisah, satu inti. Tidak ada yang bertele-tele.</p>
                    </div>

                    <div class="rounded-2xl border border-stone-200/60 bg-stone-50 p-8 sm:p-10 md:col-span-4 dark:border-white/[0.06] dark:bg-white/[0.03]">
                        <h3 class="font-serif text-xl font-bold tracking-tight">Terpercaya</h3>
                        <p class="mt-3 text-sm leading-relaxed text-stone-500 dark:text-stone-400">Setiap fakta ditelusuri dan setiap sumber dicantumkan. Pembaca berhak tahu dari mana cerita berasal.</p>
                    </div>
                </div>
            </section>

            {{-- Statistik --}}
            <section class="reveal border-t border-stone-200/80 py-16 sm:py-20 dark:border-white/[0.06]">
                <div class="grid grid-cols-2 gap-x-6 gap-y-10 md:grid-cols-5">
                    <div>
                        <p class="font-serif text-4xl font-bold tracking-tight text-stone-900 dark:text-stone-100 sm:text-5xl">{{ number_format($statistik['artikel']) }}</p>
                        <p class="mt-2 text-sm text-stone-500 dark:text-stone-400">Artikel terbit</p>
                    </div>
                    <div>
                        <p class="font-serif text-4xl font-bold tracking-tight text-stone-900 dark:text-stone-100 sm:text-5xl">{{ number_format($statistik['kategori']) }}</p>
                        <p class="mt-2 text-sm text-stone-500 dark:text-stone-400">Kategori</p>
                    </div>
                    <div>
                        <p class="font-serif text-4xl font-bold tracking-tight text-stone-900 dark:text-stone-100 sm:text-5xl">{{ number_format($statistik['era']) }}</p>
                        <p class="mt-2 text-sm text-stone-500 dark:text-stone-400">Era sejarah</p>
                    </div>
                    <div>
                        <p class="font-serif text-4xl font-bold tracking-tight text-stone-900 dark:text-stone-100 sm:text-5xl">{{ number_format($statistik['topik']) }}</p>
                        <p class="mt-2 text-sm text-stone-500 dark:text-stone-400">Topik</p>
                    </div>
                    <div>
                        <p class="font-serif text-4xl font-bold tracking-tight text-stone-900 dark:text-stone-100 sm:text-5xl">{{ number_format($statistik['pembaca']) }}</p>
                        <p class="mt-2 text-sm text-stone-500 dark:text-stone-400">Total dibaca</p>
                    </div>
                </div>
            </section>

            {{-- Newsletter --}}
            <section id="berlangganan" class="reveal overflow-hidden rounded-2xl bg-stone-900 px-6 py-14 sm:px-12 sm:py-16 dark:bg-white/[0.04]">
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
                    <form id="newsletter-form-about" method="POST" action="{{ route('newsletter.subscribe') }}" class="mt-6 flex flex-col gap-3 sm:flex-row">
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
            document.getElementById('newsletter-form-about')?.addEventListener('submit', async (e) => {
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