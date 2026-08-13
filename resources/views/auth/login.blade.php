<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Masuk — {{ config('app.name', 'Look at History') }}</title>

        <link rel="icon" href="{{ asset('favicon.ico') }}">

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-[#faf9f7] dark:bg-[#0f0f0e] text-[#171717] dark:text-[#e5e5e3] font-sans antialiased">
        <div class="flex min-h-[100dvh] flex-col lg:flex-row">
            {{-- Left Panel: Branding --}}
            <div class="relative hidden min-h-[300px] overflow-hidden bg-stone-900 lg:block lg:w-5/12 xl:w-[45%]">
                <img src="https://picsum.photos/seed/history-login/1200/900" alt="" class="absolute inset-0 h-full w-full object-cover opacity-60" loading="eager">
                <div class="absolute inset-0 bg-gradient-to-br from-stone-900/80 via-stone-900/60 to-stone-900/40 dark:from-[#0f0f0e]/90 dark:via-[#0f0f0e]/70 dark:to-[#0f0f0e]/50"></div>

                <div class="relative z-10 flex min-h-[100dvh] flex-col justify-between p-10 xl:p-14">
                    <a href="/" class="flex items-center gap-3">
                        <img src="{{ asset('logo_LAH.jpg') }}" alt="{{ config('app.name', 'Look at History') }}" class="h-10 w-10 rounded-full object-cover ring-1 ring-white/20">
                        <span class="font-serif text-xl font-bold tracking-tight text-white">Look at History</span>
                    </a>

                    <div class="max-w-md">
                        <blockquote class="font-serif text-2xl font-bold leading-snug text-white xl:text-3xl">
                            "Sejarah adalah saksi waktu, kebenaran hidup, pengingat kehidupan, dan pembawa kehidupan."
                        </blockquote>
                        <p class="mt-4 text-sm text-white/60">— Cicero</p>
                    </div>

                    <p class="text-xs text-white/40">&copy; {{ date('Y') }} {{ config('app.name', 'Look at History') }}</p>
                </div>
            </div>

            {{-- Right Panel: Login Form --}}
            <div class="flex flex-1 flex-col justify-center px-6 py-12 sm:px-8 lg:px-12 xl:px-16">
                <div class="mx-auto w-full max-w-sm">
                    {{-- Mobile Logo --}}
                    <div class="mb-10 lg:hidden">
                        <a href="/" class="flex items-center gap-3">
                            <img src="{{ asset('logo_LAH.jpg') }}" alt="{{ config('app.name', 'Look at History') }}" class="h-9 w-9 rounded-full object-cover ring-1 ring-stone-200 dark:ring-white/10">
                            <span class="font-serif text-lg font-bold tracking-tight">Look at History</span>
                        </a>
                    </div>

                    <div>
                        <h1 class="font-serif text-2xl font-bold tracking-tight sm:text-3xl">Masuk ke Akun</h1>
                        <p class="mt-2 text-sm text-stone-500 dark:text-stone-400">
                            Masukkan kredensial Anda untuk mengakses panel penulis.
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="mt-6 rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-900/30 dark:bg-red-900/10">
                            <div class="flex items-start gap-3">
                                <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-red-600 dark:text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/>
                                </svg>
                                <div class="text-sm text-red-700 dark:text-red-300">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-semibold text-stone-700 dark:text-stone-300">Alamat Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="email"
                                placeholder="nama@contoh.com"
                                class="mt-2 block w-full rounded-lg border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder:text-stone-400 focus:border-[#1e3a5f] focus:outline-none focus:ring-1 focus:ring-[#1e3a5f] dark:border-white/10 dark:bg-white/[0.03] dark:text-[#e5e5e3] dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]"
                            >
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-semibold text-stone-700 dark:text-stone-300">Kata Sandi</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="mt-2 block w-full rounded-lg border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder:text-stone-400 focus:border-[#1e3a5f] focus:outline-none focus:ring-1 focus:ring-[#1e3a5f] dark:border-white/10 dark:bg-white/[0.03] dark:text-[#e5e5e3] dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]"
                            >
                        </div>

                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="flex items-center gap-2">
                                <input
                                    type="checkbox"
                                    id="remember_me"
                                    name="remember"
                                    class="h-4 w-4 rounded border-stone-300 text-[#1e3a5f] focus:ring-[#1e3a5f] dark:border-white/20 dark:text-[#5b9bd5] dark:focus:ring-[#5b9bd5]"
                                >
                                <span class="text-sm text-stone-500 dark:text-stone-400">Ingat saya</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-[#1e3a5f] transition hover:text-[#16304a] dark:text-[#5b9bd5] dark:hover:text-[#7ab3e0]">
                                    Lupa sandi?
                                </a>
                            @endif
                        </div>

                        <button
                            type="submit"
                            class="w-full rounded-lg bg-stone-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-stone-800 active:scale-[0.98] dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200"
                        >
                            Masuk
                        </button>
                    </form>

                    <p class="mt-8 text-center text-sm text-stone-400 dark:text-stone-500">
                        Kembali ke <a href="/" class="font-semibold text-[#1e3a5f] transition hover:text-[#16304a] dark:text-[#5b9bd5] dark:hover:text-[#7ab3e0]">beranda</a>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
