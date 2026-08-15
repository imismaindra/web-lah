<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Profil — {{ config('app.name', 'Look at History') }}</title>

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
                    <a href="/" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Beranda</a>
                    <a href="{{ route('artikel.index') }}" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Artikel</a>
                    <a href="/#kategori" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Kategori</a>
                    <a href="{{ route('tentang') }}" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Tentang</a>
                </div>
                <div class="flex items-center gap-2 text-sm font-medium">
                    @auth
                        @if (auth()->user()->hasRole(['admin', 'penulis']))
                            <a href="{{ route('admin.dashboard') }}" class="rounded-lg bg-stone-900 px-4 py-2 text-white dark:bg-white dark:text-stone-900">Panel</a>
                        @endif
                        <a href="{{ route('profil.edit') }}" class="rounded-lg bg-stone-900 px-4 py-2 text-white dark:bg-white dark:text-stone-900">Profil</a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg px-3 py-1.5 text-stone-500 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Masuk</a>
                    @endauth
                </div>
            </nav>
        </header>

        <main class="mx-auto max-w-[1400px] px-5 sm:px-8">
            <section class="pt-12 pb-8 sm:pt-16">
                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-400 dark:text-stone-500">Akun</p>
                <h1 class="mt-3 font-serif text-3xl font-bold leading-[1.1] tracking-tight sm:text-4xl">Profil Saya</h1>
                <p class="mt-4 max-w-xl text-base leading-relaxed text-stone-500 dark:text-stone-400">
                    Kelola data diri dan kata sandi akun Anda.
                </p>
            </section>

            @if (session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-400">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-4 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900/30 dark:bg-red-900/10 dark:text-red-300">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="mt-8 grid gap-6 lg:grid-cols-2">
                {{-- Profile --}}
                <form method="POST" action="{{ route('profil.update') }}" class="rounded-2xl border border-stone-200/60 bg-white p-6 sm:p-8 dark:border-white/[0.06] dark:bg-[#171716]">
                    @csrf
                    @method('PUT')

                    <h2 class="font-serif text-lg font-bold tracking-tight">Data Diri</h2>
                    <p class="mt-1 text-sm text-stone-500 dark:text-stone-400">Nama dan email yang tampil di situs.</p>

                    <div class="mt-6 space-y-5">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-stone-700 dark:text-stone-300">Nama Lengkap</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', auth()->user()->name) }}"
                                required
                                class="mt-2 block w-full rounded-lg border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder:text-stone-400 focus:border-[#1e3a5f] focus:outline-none focus:ring-1 focus:ring-[#1e3a5f] dark:border-white/10 dark:bg-white/[0.03] dark:text-[#e5e5e3] dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]"
                            >
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-stone-700 dark:text-stone-300">Alamat Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', auth()->user()->email) }}"
                                required
                                class="mt-2 block w-full rounded-lg border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder:text-stone-400 focus:border-[#1e3a5f] focus:outline-none focus:ring-1 focus:ring-[#1e3a5f] dark:border-white/10 dark:bg-white/[0.03] dark:text-[#e5e5e3] dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]"
                            >
                        </div>

                        <button
                            type="submit"
                            class="w-full rounded-lg bg-[#1e3a5f] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#16304a] active:scale-[0.98] dark:bg-[#5b9bd5] dark:text-[#0f0f0e] dark:hover:bg-[#7ab3e0]"
                        >
                            Simpan Profil
                        </button>
                    </div>
                </form>

                {{-- Password --}}
                <form method="POST" action="{{ route('profil.password') }}" class="rounded-2xl border border-stone-200/60 bg-white p-6 sm:p-8 dark:border-white/[0.06] dark:bg-[#171716]">
                    @csrf
                    @method('PUT')

                    <h2 class="font-serif text-lg font-bold tracking-tight">Kata Sandi</h2>
                    <p class="mt-1 text-sm text-stone-500 dark:text-stone-400">Ganti kata sandi secara berkala demi keamanan.</p>

                    <div class="mt-6 space-y-5">
                        <div>
                            <label for="current_password" class="block text-sm font-semibold text-stone-700 dark:text-stone-300">Kata Sandi Saat Ini</label>
                            <input
                                type="password"
                                id="current_password"
                                name="current_password"
                                required
                                autocomplete="current-password"
                                class="mt-2 block w-full rounded-lg border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder:text-stone-400 focus:border-[#1e3a5f] focus:outline-none focus:ring-1 focus:ring-[#1e3a5f] dark:border-white/10 dark:bg-white/[0.03] dark:text-[#e5e5e3] dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]"
                            >
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-semibold text-stone-700 dark:text-stone-300">Kata Sandi Baru</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Minimal 8 karakter"
                                class="mt-2 block w-full rounded-lg border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder:text-stone-400 focus:border-[#1e3a5f] focus:outline-none focus:ring-1 focus:ring-[#1e3a5f] dark:border-white/10 dark:bg-white/[0.03] dark:text-[#e5e5e3] dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]"
                            >
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-stone-700 dark:text-stone-300">Konfirmasi Kata Sandi Baru</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                class="mt-2 block w-full rounded-lg border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder:text-stone-400 focus:border-[#1e3a5f] focus:outline-none focus:ring-1 focus:ring-[#1e3a5f] dark:border-white/10 dark:bg-white/[0.03] dark:text-[#e5e5e3] dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]"
                            >
                        </div>

                        <button
                            type="submit"
                            class="w-full rounded-lg bg-[#1e3a5f] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#16304a] active:scale-[0.98] dark:bg-[#5b9bd5] dark:text-[#0f0f0e] dark:hover:bg-[#7ab3e0]"
                        >
                            Ubah Kata Sandi
                        </button>
                    </div>
                </form>
            </div>

            {{-- Links --}}
            <div class="mt-8 flex flex-wrap items-center gap-3 text-sm font-medium">
                <a href="{{ route('bookmark.index') }}" class="rounded-lg border border-stone-200 px-4 py-2 text-stone-600 transition hover:border-stone-300 hover:text-stone-900 dark:border-white/10 dark:text-stone-400 dark:hover:border-white/20 dark:hover:text-white">
                    Artikel Tersimpan
                </a>
                @if (auth()->user()->hasRole(['admin', 'penulis']))
                    <a href="{{ route('admin.dashboard') }}" class="rounded-lg border border-stone-200 px-4 py-2 text-stone-600 transition hover:border-stone-300 hover:text-stone-900 dark:border-white/10 dark:text-stone-400 dark:hover:border-white/20 dark:hover:text-white">
                        Buka Panel
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="rounded-lg border border-red-200 px-4 py-2 text-red-600 transition hover:bg-red-50 dark:border-red-500/20 dark:text-red-400 dark:hover:bg-red-500/10">
                        Keluar
                    </button>
                </form>
            </div>
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