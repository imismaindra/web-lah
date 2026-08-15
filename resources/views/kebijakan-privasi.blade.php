<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Kebijakan Privasi — {{ config('app.name', 'Look at History') }}</title>

        @include('partials.seo', [
            'title' => 'Kebijakan Privasi — ' . config('app.name', 'Look at History'),
            'description' => 'Kebijakan privasi Look at History: data apa yang kami kumpulkan, bagaimana kami menggunakannya, dan hak Anda atas data tersebut.',
            'url' => route('privasi'),
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
                    <a href="{{ route('kontak') }}" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Kontak</a>
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

        <main class="mx-auto max-w-3xl px-5 sm:px-8">
            {{-- Hero --}}
            <section class="pt-12 pb-14 sm:pt-16">
                <p class="flex items-center gap-3 text-[11px] font-bold uppercase tracking-[0.18em] text-stone-400 dark:text-stone-500">
                    <span class="h-px w-10 bg-stone-300 dark:bg-white/15"></span>
                    Kebijakan Privasi
                </p>
                <h1 class="mt-6 font-serif text-4xl font-bold leading-[1.08] tracking-tight sm:text-5xl">
                    Bagaimana kami menjaga data Anda.
                </h1>
                <p class="mt-6 text-base leading-relaxed text-stone-500 dark:text-stone-400">
                    Kebijakan ini menjelaskan data apa yang kami kumpulkan saat Anda menggunakan {{ config('app.name', 'Look at History') }}, bagaimana data digunakan, dan hak Anda. Berlaku sejak {{ now()->locale('id')->translatedFormat('d F Y') }}.
                </p>
            </section>

            {{-- Document --}}
            <section class="reveal pb-20 space-y-12 text-sm leading-relaxed text-stone-600 dark:text-stone-300 sm:text-[15px]">
                <div class="space-y-3">
                    <h2 class="font-serif text-xl font-bold tracking-tight text-stone-900 dark:text-stone-100">1. Data yang kami kumpulkan</h2>
                    <p>Kami mengumpulkan data secara minimal, hanya yang diperlukan:</p>
                    <ul class="list-inside list-disc space-y-1.5">
                        <li>Alamat email, saat Anda berlangganan buletin atau mengirim pesan melalui halaman kontak.</li>
                        <li>Nama dan alamat email, saat Anda membuat akun untuk berkontribusi sebagai penulis.</li>
                        <li>Data teknis seperti alamat IP, jenis peramban, dan halaman yang dikunjungi, melalui log server dan cookie.</li>
                    </ul>
                </div>

                <div class="space-y-3">
                    <h2 class="font-serif text-xl font-bold tracking-tight text-stone-900 dark:text-stone-100">2. Bagaimana data digunakan</h2>
                    <ul class="list-inside list-disc space-y-1.5">
                        <li>Mengirim artikel dan pembaruan kepada pelanggan buletin.</li>
                        <li>Menanggapi pesan dan pertanyaan yang masuk.</li>
                        <li>Mengelola akun penulis dan proses persetujuan kontributor.</li>
                        <li>Menganalisis trafik secara agregat untuk memahami konten yang bermanfaat bagi pembaca.</li>
                    </ul>
                    <p>Kami tidak menjual data pribadi Anda kepada pihak mana pun.</p>
                </div>

                <div class="space-y-3">
                    <h2 class="font-serif text-xl font-bold tracking-tight text-stone-900 dark:text-stone-100">3. Cookie</h2>
                    <p>
                        Kami menggunakan cookie sesi untuk mendukung fungsi dasar seperti status masuk Anda.
                        Cookie ini penting untuk menjalankan situs dan tidak digunakan untuk pelacakan lintas situs.
                    </p>
                </div>

                <div class="space-y-3">
                    <h2 class="font-serif text-xl font-bold tracking-tight text-stone-900 dark:text-stone-100">4. Penyimpanan dan keamanan</h2>
                    <p>
                        Data disimpan di server yang kami kelola dengan akses terbatas. Kata sandi dienkripsi,
                        dan kami menerapkan pembatasan akses terhadap halaman administrasi.
                        Meski demikian, tidak ada metode penyimpanan digital yang sepenuhnya aman,
                        dan kami tidak dapat menjamin keamanan mutlak.
                    </p>
                </div>

                <div class="space-y-3">
                    <h2 class="font-serif text-xl font-bold tracking-tight text-stone-900 dark:text-stone-100">5. Pihak ketiga</h2>
                    <p>
                        Beberapa konten situs (misalnya gambar pendukung) dapat dimuat dari layanan pihak ketiga
                        seperti layanan penyedia gambar. Layanan tersebut mungkin mengumpulkan data sesuai kebijakannya sendiri.
                        Kami tidak mengontrol kebijakan privasi mereka.
                    </p>
                </div>

                <div class="space-y-3">
                    <h2 class="font-serif text-xl font-bold tracking-tight text-stone-900 dark:text-stone-100">6. Hak Anda</h2>
                    <ul class="list-inside list-disc space-y-1.5">
                        <li>Meminta salinan data pribadi yang kami simpan.</li>
                        <li>Meminta perbaikan data yang tidak akurat.</li>
                        <li>Berhenti berlangganan buletin kapan saja.</li>
                        <li>Meminta penghapusan akun dan data terkait.</li>
                    </ul>
                    <p>
                        Untuk menggunakan hak di atas, hubungi kami melalui
                        <a href="{{ route('kontak') }}" class="font-semibold text-[#1e3a5f] underline underline-offset-2 dark:text-[#5b9bd5]">halaman kontak</a>.
                    </p>
                </div>

                <div class="space-y-3">
                    <h2 class="font-serif text-xl font-bold tracking-tight text-stone-900 dark:text-stone-100">7. Perubahan kebijakan</h2>
                    <p>
                        Kebijakan ini dapat diperbarui sewaktu-waktu. Perubahan yang signifikan akan kami umumkan
                        melalui situs ini. Penggunaan berkelanjutan atas situs berarti Anda menerima kebijakan terbaru.
                    </p>
                </div>
            </section>
        </main>

        <footer class="border-t border-stone-200/80 dark:border-white/[0.06]">
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