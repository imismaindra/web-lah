<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Masuk') — {{ config('app.name', 'Look at History') }}</title>

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

            {{-- Right Panel: Form --}}
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
                        <h1 class="font-serif text-2xl font-bold tracking-tight sm:text-3xl">@yield('heading')</h1>
                        <p class="mt-2 text-sm text-stone-500 dark:text-stone-400">
                            @yield('subtitle')
                        </p>
                    </div>

                    @if (session('status'))
                        <div class="mt-6 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-400">
                            {{ session('status') }}
                        </div>
                    @endif

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

                    @yield('form')
                </div>
            </div>
        </div>
    </body>
</html>
