@php
    $active = $active ?? null;
@endphp

<a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-40 focus:rounded-lg focus:bg-stone-900 focus:px-4 focus:py-2 focus:text-sm focus:text-white dark:focus:bg-white dark:focus:text-stone-900">
    Lewati ke konten
</a>

<header class="sticky top-0 z-20 border-b border-stone-200/80 dark:border-white/[0.06] bg-[#faf9f7]/80 dark:bg-[#0f0f0e]/80 backdrop-blur-xl">
    <nav class="mx-auto flex max-w-[1400px] items-center justify-between px-5 py-4 sm:px-8">
        <a href="/" class="flex items-center gap-3">
            <img src="{{ asset('logo_LAH_thumb.webp') }}" onerror="this.onerror=null;this.src='{{ asset('logo_LAH.jpg') }}'" alt="{{ config('app.name', 'Look at History') }}" width="36" height="36" class="h-9 w-9 rounded-full object-cover ring-1 ring-stone-200 dark:ring-white/10">
            <span class="font-serif text-lg font-bold tracking-tight">Look at History</span>
        </a>
        <div class="hidden items-center gap-1 text-sm font-medium sm:flex">
            <a href="/" @class(['rounded-lg px-3 py-1.5 transition font-medium', 'bg-stone-900 text-white dark:bg-white dark:text-stone-900' => $active === 'beranda', 'text-stone-500 hover:text-stone-900 dark:text-stone-400 dark:hover:text-white' => $active !== 'beranda'])>Beranda</a>
            <a href="{{ route('artikel.index') }}" @class(['rounded-lg px-3 py-1.5 transition font-medium', 'bg-stone-900 text-white dark:bg-white dark:text-stone-900' => $active === 'artikel', 'text-stone-500 hover:text-stone-900 dark:text-stone-400 dark:hover:text-white' => $active !== 'artikel'])>Artikel</a>
            <a href="/#kategori" @class(['rounded-lg px-3 py-1.5 transition font-medium', 'bg-stone-900 text-white dark:bg-white dark:text-stone-900' => $active === 'kategori', 'text-stone-500 hover:text-stone-900 dark:text-stone-400 dark:hover:text-white' => $active !== 'kategori'])>Kategori</a>
            <a href="{{ route('tentang') }}" @class(['rounded-lg px-3 py-1.5 transition font-medium', 'bg-stone-900 text-white dark:bg-white dark:text-stone-900' => $active === 'tentang', 'text-stone-500 hover:text-stone-900 dark:text-stone-400 dark:hover:text-white' => $active !== 'tentang'])>Tentang</a>
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
            <a href="{{ route('search') }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-stone-500 transition hover:bg-stone-100 hover:text-stone-900 sm:hidden dark:text-stone-400 dark:hover:bg-white/10 dark:hover:text-white" aria-label="Cari artikel">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </a>
            <button type="button" id="theme-toggle" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-stone-500 transition hover:bg-stone-100 hover:text-stone-900 dark:text-stone-400 dark:hover:bg-white/10 dark:hover:text-white" aria-label="Ganti tema gelap/terang">
                <svg id="theme-icon-moon" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79Z"/></svg>
                <svg id="theme-icon-sun" class="hidden h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M2 12h2m16 0h2M4.9 4.9l1.4 1.4M17.7 6.3l1.4-1.4M19.1 19.1l-1.4-1.4M6.3 17.7l-1.4 1.4"/></svg>
            </button>
            @include('partials.mobile-nav', ['active' => $active])
        </div>
    </nav>
</header>
