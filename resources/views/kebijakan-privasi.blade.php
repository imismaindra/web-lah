<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        @include('partials.theme-init')
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

        @include('partials.site-header')

        <main id="main" class="mx-auto max-w-3xl px-5 sm:px-8">
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

        @include('partials.site-footer')
    </body>
</html>