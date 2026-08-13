<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Sriwijaya: Raksasa Maritim di Selat Malaka — {{ config('app.name', 'Look at History') }}</title>

        <link rel="icon" href="{{ asset('favicon.ico') }}">

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            .article-body p { text-indent: 1.5em; }
            .article-body p:first-child,
            .article-body h2,
            .article-body blockquote,
            .article-body .key-point { text-indent: 0; }

            @keyframes progress-fill {
                from { width: 0%; }
                to { width: 100%; }
            }

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

            @supports not (animation-timeline: scroll()) {
                .reading-progress { display: none; }
            }

            .toc-link.active {
                color: #1e3a5f;
                background: #1e3a5f/5;
            }

            :is(.dark) .toc-link.active {
                color: #5b9bd5;
                background: #5b9bd5/5;
            }

            .grain-overlay {
                position: fixed;
                inset: 0;
                pointer-events: none;
                z-index: 40;
                opacity: 0.03;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
                background-repeat: repeat;
                background-size: 200px 200px;
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
                <div class="flex items-center gap-2 text-sm font-medium">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="rounded-lg bg-stone-900 px-4 py-2 text-white dark:bg-white dark:text-stone-900">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="rounded-lg bg-stone-900 px-4 py-2 text-white dark:bg-white dark:text-stone-900">Daftar</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>
        </header>

        <main>
            {{-- Hero --}}
            <section class="relative overflow-hidden">
                <div class="absolute inset-0">
                    <img src="https://picsum.photos/seed/history-sriwijaya/1600/900" alt="" class="h-full w-full object-cover" loading="eager">
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
                            <span class="rounded-full bg-[#1e3a5f]/10 px-3 py-1 text-[#1e3a5f] dark:bg-[#5b9bd5]/10 dark:text-[#5b9bd5]">Hindu–Buddha</span>
                            <span class="text-stone-300 dark:text-stone-600">·</span>
                            <span class="text-stone-400 dark:text-stone-500">12 Mei 2026</span>
                            <span class="text-stone-300 dark:text-stone-600">·</span>
                            <span class="text-stone-400 dark:text-stone-500">5 menit baca</span>
                        </div>

                        <h1 class="mt-5 font-serif text-3xl font-bold leading-[1.1] tracking-tight sm:text-4xl lg:text-5xl">
                            Sriwijaya: Raksasa Maritim<br class="hidden sm:block"> di Selat Malaka
                        </h1>

                        <p class="mt-5 max-w-xl text-base leading-relaxed text-stone-500 dark:text-stone-400">
                            Selama lebih dari enam abad, Kerajaan Sriwijaya menguasai jalur pelayaran dan perdagangan
                            Nusantara. Namun siapa sebenarnya penguasa laut yang nyaris tak meninggalkan jejak bangunan megah ini?
                        </p>

                        <div class="mt-8 flex items-center gap-3.5">
                            <img src="{{ asset('logo_LAH.jpg') }}" alt="Redaksi" class="h-11 w-11 rounded-full object-cover ring-1 ring-stone-200 dark:ring-white/10">
                            <div class="text-sm">
                                <p class="font-semibold">Redaksi Look at History</p>
                                <p class="text-xs text-stone-400 dark:text-stone-500">Penulis</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Article Content + Sidebar --}}
            <div class="mx-auto max-w-[1400px] px-5 sm:px-8">
                <div class="grid gap-12 lg:grid-cols-[1fr_260px]">
                    {{-- Article Body --}}
                    <article class="py-8 sm:py-12">
                        <div class="article-body space-y-6 text-[17px] leading-[1.85] text-stone-600 dark:text-stone-300">
                            <p class="text-lg font-medium text-stone-900 first:text-indent-0 dark:text-[#e5e5e3]">
                                Selama lebih dari enam abad, Kerajaan Sriwijaya menguasai jalur pelayaran dan perdagangan
                                Nusantara. Namun siapa sebenarnya penguasa laut yang nyaris tak meninggalkan jejak bangunan megah ini?
                            </p>

                            <p>
                                Nama Sriwijaya pertama kali muncul dalam prasasti Kedukan Bukit yang ditemukan di tepi
                                Sungai Talang, dekat Palembang. Prasasti bertanggal 683 M ini ditulis dalam bahasa Melayu
                                kuno dan menceritakan perjalanan suci seorang raja bersama dua ribu pasukannya. Dari sinilah
                                para sejarawan meyakini pusat kerajaan berada di sekitar Palembang, Sumatra Selatan.
                            </p>

                            <p>
                                Sepanjang abad ke-7 hingga ke-12, Sriwijaya tumbuh menjadi kekuatan maritim yang
                                disegani. Pelabuhannya menjadi titik pertemuan pedagang dari Tiongkok, India, bahkan
                                Timur Tengah. Rempah, kain, dan logam mulia mengalir melalui selat yang dikendalikan
                                oleh armada Sriwijaya.
                            </p>

                            <figure class="my-10">
                                <div class="overflow-hidden rounded-2xl bg-stone-100 dark:bg-white/[0.03]">
                                    <img src="https://picsum.photos/seed/sriwijaya-armada/800/450" alt="Ilustrasi armada laut Sriwijaya" class="h-56 w-full object-cover sm:h-72" loading="lazy">
                                </div>
                                <figcaption class="mt-3 text-center text-xs text-stone-400 dark:text-stone-500">
                                    Ilustrasi: Armada laut Sriwijaya menguasai jalur perdagangan Selat Malaka.
                                </figcaption>
                            </figure>

                            <h2 id="penguasa-selat" class="pt-4 font-serif text-2xl font-bold tracking-tight text-stone-900 dark:text-[#e5e5e3]">
                                Penguasa Selat yang Menentukan Arah Komoditas
                            </h2>

                            <p>
                                Letak Sriwijaya di tepi Selat Malaka bukanlah kebetulan. Selat ini menjadi pintu masuk utama
                                kapal-kapal dari India dan Tiongkok yang membawa rempah, sutra, dan emas. Dengan menguasai
                                selat beserta pelabuhan-pelabuhan di sekitarnya, Sriwijaya dapat memungut pajak, mengendalikan
                                harga komoditas, bahkan memaksa kapal asing untuk singgah di pelabuhannya.
                            </p>

                            <blockquote class="my-8 rounded-r-2xl border-l-[3px] border-[#1e3a5f] bg-[#1e3a5f]/5 py-4 pl-6 pr-6 font-serif text-lg italic leading-relaxed text-stone-600 dark:border-[#5b9bd5] dark:bg-[#5b9bd5]/5 dark:text-stone-400">
                                "Sebuah kerajaan yang wilayahnya lebih terasa di laut daripada di darat — kekuasaan diukur
                                dari siapa yang berani berlayar melewati selatnya."
                            </blockquote>

                            <p>
                                Kekuasaan maritim ini juga menjadikan Sriwijaya pusat pembelajaran agama Buddha yang
                                disegani. I Tsing, seorang biksu Tiongkok, singgah di Sriwijaya selama bertahun-tahun dan
                                mencatat bahwa ribuan biksu belajar di sana. Kerajaan ini juga membangun hubungan diplomatik
                                dengan Dinasti Tang dan Dinasti Chola di India selatan.
                            </p>

                            <h2 id="kehancuran" class="pt-4 font-serif text-2xl font-bold tracking-tight text-stone-900 dark:text-[#e5e5e3]">
                                Kehancuran dan Warisan yang Tersisa
                            </h2>

                            <p>
                                Kekuasaan Sriwijaya mulai goyah pada abad ke-11 setelah serangan besar dari Kerajaan Chola
                                pada tahun 1025. Meski mampu pulih sebagian, Sriwijaya tak pernah kembali ke masa kejayaannya.
                                Pergeseran jalur perdagangan dan bangkitnya kekuatan baru di Jawa dan Sumatra akhirnya
                                mengakhiri dominasi kerajaan ini pada abad ke-13.
                            </p>

                            <p>
                                Kendati istananya tak pernah ditemukan utuh, warisan Sriwijaya tetap terasa. Bahasa Melayu
                                yang dipakainya menjadi cikal bakal bahasa Indonesia. Konsep kekuasaan maritimnya menjadi
                                rujukan bagi bangsa-bangsa di Nusantara. Dari prasasti dan catatan asing, kita tahu: jauh
                                sebelum kapal-kapal Eropa tiba, Nusantara sudah punya raksasa lautnya sendiri.
                            </p>

                            <div class="key-point my-10 rounded-2xl border border-[#1e3a5f]/20 bg-[#1e3a5f]/5 p-6 dark:border-[#5b9bd5]/20 dark:bg-[#5b9bd5]/5">
                                <div class="mb-4 flex items-center gap-2.5">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#1e3a5f]/10 dark:bg-[#5b9bd5]/10">
                                        <svg class="h-4 w-4 text-[#1e3a5f] dark:text-[#5b9bd5]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/>
                                        </svg>
                                    </div>
                                    <h3 class="font-serif text-lg font-bold text-[#1e3a5f] dark:text-[#5b9bd5]">Poin Penting</h3>
                                </div>
                                <ul class="list-disc space-y-2 pl-5 text-sm leading-relaxed">
                                    <li>Berdiri sekitar abad ke-7, pusat di kawasan Palembang.</li>
                                    <li>Menguasai Selat Malaka sebagai poros perdagangan dunia.</li>
                                    <li>Pusat agama Buddha yang dikunjungi I Tsing pada abad ke-7.</li>
                                    <li>Melemah setelah serangan Chola (1025 M) dan runtuh di abad ke-13.</li>
                                </ul>
                            </div>
                        </div>

                        {{-- Tags --}}
                        <div class="flex flex-wrap gap-2 border-t border-stone-200/80 pt-6 dark:border-white/[0.06]">
                            <a href="#" class="rounded-full bg-stone-100 px-3.5 py-1.5 text-xs font-semibold text-stone-500 transition hover:bg-[#1e3a5f]/10 hover:text-[#1e3a5f] dark:bg-white/[0.05] dark:text-stone-400 dark:hover:text-[#5b9bd5]">#Sejarah</a>
                            <a href="#" class="rounded-full bg-stone-100 px-3.5 py-1.5 text-xs font-semibold text-stone-500 transition hover:bg-[#1e3a5f]/10 hover:text-[#1e3a5f] dark:bg-white/[0.05] dark:text-stone-400 dark:hover:text-[#5b9bd5]">#Sriwijaya</a>
                            <a href="#" class="rounded-full bg-stone-100 px-3.5 py-1.5 text-xs font-semibold text-stone-500 transition hover:bg-[#1e3a5f]/10 hover:text-[#1e3a5f] dark:bg-white/[0.05] dark:text-stone-400 dark:hover:text-[#5b9bd5]">#Maritim</a>
                            <a href="#" class="rounded-full bg-stone-100 px-3.5 py-1.5 text-xs font-semibold text-stone-500 transition hover:bg-[#1e3a5f]/10 hover:text-[#1e3a5f] dark:bg-white/[0.05] dark:text-stone-400 dark:hover:text-[#5b9bd5]">#Nusantara</a>
                        </div>

                        {{-- Author Bio --}}
                        <div class="mt-10 rounded-2xl border border-stone-200/60 bg-white p-6 sm:p-8 dark:border-white/[0.06] dark:bg-[#171716]">
                            <div class="flex items-start gap-4">
                                <img src="{{ asset('logo_LAH.jpg') }}" alt="Redaksi" class="h-14 w-14 flex-shrink-0 rounded-full object-cover ring-1 ring-stone-200 dark:ring-white/10">
                                <div>
                                    <p class="font-semibold">Redaksi Look at History</p>
                                    <p class="mt-1 text-sm leading-relaxed text-stone-500 dark:text-stone-400">Menulis tentang sejarah untuk pembaca yang ingin memahami masa lalu. Artikel ditulis berdasarkan referensi sejarah terpercaya.</p>
                                    <a href="/" class="mt-3 inline-block text-sm font-semibold text-[#1e3a5f] transition hover:text-[#16304a] dark:text-[#5b9bd5] dark:hover:text-[#7ab3e0]">Lihat semua artikel &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </article>

                    {{-- Sidebar --}}
                    <aside class="hidden lg:block">
                        <div class="sticky top-24 space-y-8">
                            {{-- Table of Contents --}}
                            <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
                                <h2 class="mb-3 text-xs font-bold uppercase tracking-widest text-stone-400 dark:text-stone-500">Daftar Isi</h2>
                                <nav class="space-y-0.5">
                                    <a href="#penguasa-selat" class="toc-link block rounded-lg px-3 py-2 text-sm text-stone-500 transition hover:bg-stone-50 hover:text-stone-900 dark:text-stone-400 dark:hover:bg-white/[0.03] dark:hover:text-white">
                                        Penguasa Selat
                                    </a>
                                    <a href="#kehancuran" class="toc-link block rounded-lg px-3 py-2 text-sm text-stone-500 transition hover:bg-stone-50 hover:text-stone-900 dark:text-stone-400 dark:hover:bg-white/[0.03] dark:hover:text-white">
                                        Kehancuran dan Warisan
                                    </a>
                                </nav>
                            </div>

                            {{-- Newsletter --}}
                            <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
                                <h2 class="font-serif text-base font-bold">Tidak Ketinggalan Cerita</h2>
                                <p class="mt-2 text-sm leading-relaxed text-stone-500 dark:text-stone-400">
                                    Artikel sejarah terbaru langsung di inbox kamu.
                                </p>
                                <form class="mt-4 space-y-2.5">
                                    <input type="email" placeholder="email@kamu.com" class="w-full rounded-lg border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-sm placeholder:text-stone-400 focus:border-[#1e3a5f] focus:outline-none focus:ring-1 focus:ring-[#1e3a5f] dark:border-white/10 dark:bg-white/[0.03] dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]">
                                    <button type="submit" class="w-full rounded-lg bg-stone-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-stone-800 dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200">Berlangganan</button>
                                </form>
                                <p class="mt-2 text-[11px] text-stone-400 dark:text-stone-500">Kami hormati privasi kamu.</p>
                            </div>
                        </div>
                    </aside>
                </div>

                {{-- Related Articles --}}
                <section class="border-t border-stone-200/80 py-12 dark:border-white/[0.06] sm:py-16">
                    <h2 class="font-serif text-2xl font-bold tracking-tight">Baca Juga</h2>
                    <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        <a href="#" class="group overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
                            <div class="relative overflow-hidden bg-stone-100 dark:bg-stone-800/50">
                                <img src="https://picsum.photos/seed/history-borobudur/600/400" alt="Borobudur" class="h-44 w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                            </div>
                            <div class="p-5">
                                <div class="flex items-center gap-2 text-xs font-semibold">
                                    <span class="text-[#1e3a5f] dark:text-[#5b9bd5]">Hindu–Buddha</span>
                                    <span class="text-stone-300 dark:text-stone-600">·</span>
                                    <span class="text-stone-400 dark:text-stone-500">2 Mei 2026</span>
                                </div>
                                <h3 class="mt-3 font-serif text-base font-bold leading-snug group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">
                                    Borobudur: Keajaiban Dunia dari Dinasti Syailendra
                                </h3>
                                <p class="mt-2 text-xs leading-relaxed text-stone-500 dark:text-stone-400">
                                    Satu juta batu, 2.672 panel relief, dan makna tersembunyi di balik stupa terbesar.
                                </p>
                            </div>
                        </a>

                        <a href="#" class="group overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
                            <div class="relative overflow-hidden bg-stone-100 dark:bg-stone-800/50">
                                <img src="https://picsum.photos/seed/history-diponegoro/600/400" alt="Diponegoro" class="h-44 w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                            </div>
                            <div class="p-5">
                                <div class="flex items-center gap-2 text-xs font-semibold">
                                    <span class="text-[#1e3a5f] dark:text-[#5b9bd5]">Kolonial</span>
                                    <span class="text-stone-300 dark:text-stone-600">·</span>
                                    <span class="text-stone-400 dark:text-stone-500">19 April 2026</span>
                                </div>
                                <h3 class="mt-3 font-serif text-base font-bold leading-snug group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">
                                    Perang Diponegoro: Perlawanan Terpanjang Abad ke-19
                                </h3>
                                <p class="mt-2 text-xs leading-relaxed text-stone-500 dark:text-stone-400">
                                    Lima tahun perang besar yang melahirkan strategi perang gerilya.
                                </p>
                            </div>
                        </a>

                        <a href="#" class="group overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
                            <div class="relative overflow-hidden bg-stone-100 dark:bg-stone-800/50">
                                <img src="https://picsum.photos/seed/history-proklamasi/600/400" alt="Proklamasi" class="h-44 w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                            </div>
                            <div class="p-5">
                                <div class="flex items-center gap-2 text-xs font-semibold">
                                    <span class="text-[#1e3a5f] dark:text-[#5b9bd5]">Kemerdekaan</span>
                                    <span class="text-stone-300 dark:text-stone-600">·</span>
                                    <span class="text-stone-400 dark:text-stone-500">5 April 2026</span>
                                </div>
                                <h3 class="mt-3 font-serif text-base font-bold leading-snug group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">
                                    Detik-Detik Proklamasi 17 Agustus 1945
                                </h3>
                                <p class="mt-2 text-xs leading-relaxed text-stone-500 dark:text-stone-400">
                                    Dari Rengasdengklok hingga pembacaan teks proklamasi: 48 jam yang menentukan.
                                </p>
                            </div>
                        </a>
                    </div>
                </section>
            </div>
        </main>

        <footer class="border-t border-stone-200/80 dark:border-white/[0.06]">
            <div class="mx-auto flex max-w-[1400px] flex-col items-center justify-between gap-4 px-5 py-8 text-sm text-stone-400 sm:flex-row sm:px-8 dark:text-stone-500">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Look at History') }}.</p>
                <p>Belajar Sejarah</p>
            </div>
        </footer>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const headings = document.querySelectorAll('.article-body h2[id]');
                const tocLinks = document.querySelectorAll('.toc-link');

                if (!headings.length || !tocLinks.length) return;

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            tocLinks.forEach(link => {
                                link.classList.toggle('active', link.getAttribute('href') === '#' + entry.target.id);
                            });
                        }
                    });
                }, { rootMargin: '-20% 0px -60% 0px' });

                headings.forEach(heading => observer.observe(heading));
            });
        </script>
    </body>
</html>
