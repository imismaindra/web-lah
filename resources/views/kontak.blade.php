<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Kontak — {{ config('app.name', 'Look at History') }}</title>

        @include('partials.seo', [
            'title' => 'Kontak — ' . config('app.name', 'Look at History'),
            'description' => 'Hubungi redaksi Look at History untuk pertanyaan, saran, atau kerja sama. Kami membaca setiap pesan.',
            'url' => route('kontak'),
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
                    <a href="{{ route('tentang') }}" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Tentang</a>
                    <a href="{{ route('kontak') }}" class="rounded-lg bg-stone-900 px-3 py-1.5 text-white dark:bg-white dark:text-stone-900">Kontak</a>
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
            <section class="pt-12 pb-16 sm:pt-16 sm:pb-20">
                <div class="max-w-3xl">
                    <p class="flex items-center gap-3 text-[11px] font-bold uppercase tracking-[0.18em] text-stone-400 dark:text-stone-500">
                        <span class="h-px w-10 bg-stone-300 dark:bg-white/15"></span>
                        Hubungi Kami
                    </p>
                    <h1 class="mt-6 font-serif text-4xl font-bold leading-[1.08] tracking-tight sm:text-5xl lg:text-6xl">
                        Ada pertanyaan, saran, atau ide cerita?
                    </h1>
                    <p class="mt-6 max-w-xl text-base leading-relaxed text-stone-500 dark:text-stone-400">
                        Kami membaca setiap pesan. Ceritakan apa yang ingin kamu sampaikan, dan tim redaksi akan merespons secepatnya.
                    </p>
                </div>
            </section>

            {{-- Contact Channels --}}
            <section class="reveal grid gap-5 md:grid-cols-2">
                <a href="mailto:{{ config('mail.from.address') }}" class="group flex items-center gap-5 rounded-2xl border border-stone-200/60 bg-white p-6 transition hover:border-stone-300 dark:border-white/[0.06] dark:bg-[#171716] dark:hover:border-white/10">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-[#1e3a5f]/10 text-[#1e3a5f] dark:bg-[#5b9bd5]/10 dark:text-[#5b9bd5]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-stone-400 dark:text-stone-500">Email</p>
                        <p class="mt-1 truncate font-serif text-base font-bold tracking-tight group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">{{ config('mail.from.address') }}</p>
                    </div>
                </a>

                <a href="https://www.instagram.com/lookathistory/" target="_blank" rel="noopener noreferrer" class="group flex items-center gap-5 rounded-2xl border border-stone-200/60 bg-white p-6 transition hover:border-stone-300 dark:border-white/[0.06] dark:bg-[#171716] dark:hover:border-white/10">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#feda75] via-[#d62976] to-[#962fbf]">
                        <img src="https://cdn.simpleicons.org/instagram/ffffff" alt="Instagram" class="h-5 w-5">
                    </div>
                    <div class="min-w-0">
                        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-stone-400 dark:text-stone-500">Instagram</p>
                        <p class="mt-1 font-serif text-base font-bold tracking-tight group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">@lookathistory</p>
                    </div>
                </a>
            </section>

            {{-- Form --}}
            <section class="reveal mt-14 grid gap-10 lg:grid-cols-[0.85fr_1.15fr] lg:items-start lg:gap-16">
                <div>
                    <h2 class="font-serif text-2xl font-bold tracking-tight sm:text-3xl">Tulis pesan</h2>
                    <p class="mt-3 max-w-md text-sm leading-relaxed text-stone-500 dark:text-stone-400">
                        Gunakan formulir di samping untuk kirim pesan langsung ke redaksi. Balasan dikirim ke email yang kamu cantumkan.
                    </p>
                    <p class="mt-5 max-w-md text-sm leading-relaxed text-stone-500 dark:text-stone-400">
                        Untuk koreksi fakta artikel, sertakan judul artikel dan tautannya agar kami bisa menindaklanjuti lebih cepat.
                    </p>
                </div>

                <div class="rounded-2xl border border-stone-200/60 bg-white p-6 sm:p-8 dark:border-white/[0.06] dark:bg-[#171716]">
                    @if (session('status'))
                        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-400">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-300">
                            <ul class="list-inside list-disc space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('kontak.store') }}" class="space-y-5">
                        @csrf

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="name" class="mb-1.5 block text-sm font-semibold text-stone-700 dark:text-stone-300">Nama</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    placeholder="Nama kamu"
                                    class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-2.5 text-sm text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10"
                                >
                            </div>
                            <div>
                                <label for="email" class="mb-1.5 block text-sm font-semibold text-stone-700 dark:text-stone-300">Email</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    placeholder="email@kamu.com"
                                    class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-2.5 text-sm text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10"
                                >
                            </div>
                        </div>

                        <div>
                            <label for="message" class="mb-1.5 block text-sm font-semibold text-stone-700 dark:text-stone-300">Pesan</label>
                            <textarea
                                id="message"
                                name="message"
                                rows="6"
                                required
                                placeholder="Tulis pesanmu di sini..."
                                class="w-full resize-none rounded-xl border border-stone-200 bg-stone-50 px-4 py-2.5 text-sm text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10"
                            >{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="w-full rounded-xl bg-stone-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-stone-800 active:scale-[0.98] sm:w-auto dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200">
                            Kirim Pesan
                        </button>
                    </form>
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
    </body>
</html>