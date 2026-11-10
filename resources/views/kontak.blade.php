<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        @include('partials.theme-init')
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Kontak | {{ config('app.name', 'Look at History') }}</title>

        @include('partials.seo', [
            'title' => 'Kontak | ' . config('app.name', 'Look at History'),
            'description' => 'Hubungi redaksi Look at History untuk pertanyaan, saran, atau kerja sama. Kami membaca setiap pesan.',
            'url' => route('kontak'),
        ])

        <link rel="icon" href="{{ asset('favicon.ico') }}">

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
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
        @include('partials.site-header')

        <main id="main" class="mx-auto max-w-[1400px] px-5 sm:px-8">
            {{-- Hero --}}
            <section class="pt-12 pb-16 sm:pt-16 sm:pb-20">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-400 dark:text-stone-500">
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
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-[#1e3a5f]/10 text-[#1e3a5f] dark:bg-white/10 dark:text-stone-200">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-stone-400 dark:text-stone-500">Email</p>
                        <p class="mt-1 truncate font-serif text-base font-bold tracking-tight group-hover:text-[#1e3a5f] dark:group-hover:text-white">{{ config('mail.from.address') }}</p>
                    </div>
                </a>

                <a href="https://www.instagram.com/lookathistory/" target="_blank" rel="noopener noreferrer" class="group flex items-center gap-5 rounded-2xl border border-stone-200/60 bg-white p-6 transition hover:border-stone-300 dark:border-white/[0.06] dark:bg-[#171716] dark:hover:border-white/10">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#feda75] via-[#d62976] to-[#962fbf] text-white">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-stone-400 dark:text-stone-500">Instagram</p>
                        <p class="mt-1 font-serif text-base font-bold tracking-tight group-hover:text-[#1e3a5f] dark:group-hover:text-white">@lookathistory</p>
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
                                    class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-2.5 text-sm text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-stone-400 dark:focus:ring-stone-400/20"
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
                                    class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-2.5 text-sm text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-stone-400 dark:focus:ring-stone-400/20"
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
                                class="w-full resize-none rounded-xl border border-stone-200 bg-stone-50 px-4 py-2.5 text-sm text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-stone-400 dark:focus:ring-stone-400/20"
                            >{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="w-full rounded-xl bg-stone-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-stone-800 active:scale-[0.98] sm:w-auto dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200">
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            </section>
        </main>

        @include('partials.site-footer')
    </body>
</html>