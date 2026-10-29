@php
    $menuLinks = [
        'beranda' => ['label' => 'Beranda', 'url' => '/'],
        'artikel' => ['label' => 'Artikel', 'url' => route('artikel.index')],
        'kategori' => ['label' => 'Kategori', 'url' => '/#kategori'],
        'tentang' => ['label' => 'Tentang', 'url' => route('tentang')],
    ];
@endphp

<button
    type="button"
    id="mobile-menu-btn"
    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-stone-600 transition hover:bg-stone-100 sm:hidden dark:text-stone-300 dark:hover:bg-white/10"
    aria-label="Buka menu"
    aria-expanded="false"
    aria-controls="mobile-menu"
>
    <svg id="mobile-menu-icon-open" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16"/><path d="M4 12h16"/><path d="M4 17h16"/></svg>
    <svg id="mobile-menu-icon-close" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12"/><path d="M18 6L6 18"/></svg>
</button>

<div id="mobile-menu" class="absolute inset-x-0 top-full hidden border-b border-stone-200/80 bg-[#faf9f7] sm:hidden dark:border-white/[0.06] dark:bg-[#0f0f0e]">
    <div class="mx-auto max-w-[1400px] space-y-1 px-5 py-4 sm:px-8">
        @foreach ($menuLinks as $key => $link)
            <a
                href="{{ $link['url'] }}"
                @class([
                    'block rounded-lg px-3 py-2 text-sm font-semibold',
                    'bg-stone-900 text-white dark:bg-white dark:text-stone-900' => ($active ?? null) === $key,
                    'text-stone-600 transition hover:text-stone-900 dark:text-stone-300 dark:hover:text-white' => ($active ?? null) !== $key,
                ])
            >{{ $link['label'] }}</a>
        @endforeach

        <form action="{{ route('search') }}" method="GET" class="relative pt-2">
            <input
                type="text"
                name="q"
                placeholder="Cari artikel..."
                class="w-full rounded-lg border border-stone-200 bg-white px-3.5 py-2 pl-9 text-sm placeholder:text-stone-400 focus:border-[#1e3a5f] focus:outline-none focus:ring-1 focus:ring-[#1e3a5f] dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]"
            >
            <svg class="absolute left-3 top-[1.15rem] h-4 w-4 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        </form>

        <div class="border-t border-stone-200/70 pt-3 mt-3 dark:border-white/[0.06]">
            @if (Route::has('login'))
                @auth
                    @if (auth()->user()->hasRole(['admin', 'penulis']))
                        <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold text-stone-600 transition hover:text-stone-900 dark:text-stone-300 dark:hover:text-white">Panel Admin</a>
                    @endif
                    <a href="{{ route('profil.edit') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold text-stone-600 transition hover:text-stone-900 dark:text-stone-300 dark:hover:text-white">Profil Saya</a>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left rounded-lg px-3 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block rounded-lg bg-stone-900 px-3 py-2.5 text-center text-sm font-semibold text-white transition hover:bg-stone-800 dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200">Masuk</a>
                @endauth
            @endif
        </div>
    </div>
</div>

<script>
    (function () {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const iconOpen = document.getElementById('mobile-menu-icon-open');
        const iconClose = document.getElementById('mobile-menu-icon-close');
        if (!btn || !menu) return;

        function setOpen(open) {
            menu.classList.toggle('hidden', !open);
            iconOpen.classList.toggle('hidden', open);
            iconClose.classList.toggle('hidden', !open);
            btn.setAttribute('aria-expanded', String(open));
            btn.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
        }

        btn.addEventListener('click', () => setOpen(menu.classList.contains('hidden')));
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') setOpen(false);
        });
        menu.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setOpen(false)));
    })();
</script>
