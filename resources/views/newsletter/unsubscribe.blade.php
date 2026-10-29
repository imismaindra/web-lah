<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        @include('partials.theme-init')
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Berhenti Berlangganan — {{ config('app.name', 'Look at History') }}</title>

        <link rel="icon" href="{{ asset('favicon.ico') }}">

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-[#faf9f7] dark:bg-[#0f0f0e] text-[#171717] dark:text-[#e5e5e3] font-sans antialiased">
        @include('partials.site-header')

        <main class="flex min-h-[70vh] items-center justify-center px-5 py-16 sm:px-8">
            <div class="w-full max-w-md">
                @if (session('status'))
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6 text-center dark:border-emerald-500/20 dark:bg-emerald-500/10">
                        <svg class="mx-auto h-10 w-10 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        <h1 class="mt-4 font-serif text-xl font-bold tracking-tight text-emerald-700 dark:text-emerald-400">Berhasil berhenti berlangganan</h1>
                        <p class="mt-2 text-sm text-emerald-600 dark:text-emerald-500">{{ session('status') }}</p>
                        <a href="/" class="mt-6 inline-block text-sm font-semibold text-[#1e3a5f] transition hover:text-[#16304a] dark:text-[#5b9bd5]">Kembali ke beranda &rarr;</a>
                    </div>
                @elseif ($subscriber)
                    <div class="rounded-2xl border border-stone-200/60 bg-white p-8 text-center dark:border-white/[0.06] dark:bg-[#171716]">
                        <svg class="mx-auto h-10 w-10 text-stone-300 dark:text-stone-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        <h1 class="mt-4 font-serif text-xl font-bold tracking-tight">Berhenti berlangganan?</h1>
                        <p class="mt-2 text-sm leading-relaxed text-stone-500 dark:text-stone-400">
                            Email <strong class="text-stone-700 dark:text-stone-300">{{ $subscriber->email }}</strong> akan dihapus dari daftar dan tidak menerima buletin lagi.
                        </p>
                        <form method="POST" action="{{ route('newsletter.unsubscribe.destroy', $subscriber->token) }}" class="mt-6">
                            @csrf
                            <button type="submit" class="w-full rounded-xl bg-red-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-600 active:scale-[0.98]">
                                Ya, berhenti berlangganan
                            </button>
                        </form>
                        <a href="/" class="mt-4 block text-sm font-semibold text-[#1e3a5f] transition hover:text-[#16304a] dark:text-[#5b9bd5]">Tidak, batalkan</a>
                    </div>
                @else
                    <div class="rounded-2xl border border-stone-200/60 bg-white p-8 text-center dark:border-white/[0.06] dark:bg-[#171716]">
                        <h1 class="font-serif text-xl font-bold tracking-tight">Link tidak validd</h1>
                        <p class="mt-2 text-sm text-stone-500 dark:text-stone-400">Tautan berhenti berlangganan ini tidak valid atau sudah dipakai.</p>
                        <a href="/" class="mt-6 inline-block text-sm font-semibold text-[#1e3a5f] transition hover:text-[#16304a] dark:text-[#5b9bd5]">Kembali ke beranda &rarr;</a>
                    </div>
                @endif
            </div>
        </main>

        @include('partials.site-footer')
    </body>
</html>