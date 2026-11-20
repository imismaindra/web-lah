<footer class="mt-20 border-t border-stone-200/80 bg-stone-50/50 dark:border-white/[0.06] dark:bg-[#121211]/50">
    <div class="mx-auto max-w-[1400px] px-5 py-12 sm:px-8 sm:py-16">
        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4 lg:gap-12">
            {{-- Col 1: Brand & Deskripsi --}}
            <div class="space-y-4 sm:col-span-2 lg:col-span-1">
                <a href="/" class="flex items-center gap-3">
                    <img src="{{ asset('logo_LAH_thumb.webp') }}" onerror="this.onerror=null;this.src='{{ asset('logo_LAH.jpg') }}'" alt="{{ config('app.name', 'Look at History') }}" width="32" height="32" class="h-8 w-8 rounded-full object-cover ring-1 ring-stone-200 dark:ring-white/10">
                    <span class="font-serif text-base font-bold tracking-tight text-stone-900 dark:text-white">Look at History</span>
                </a>
                <p class="max-w-sm text-xs leading-relaxed text-stone-500 dark:text-stone-400">
                    Blog sejarah dunia ringkas & terpercaya. Menjelajahi peradaban kuno, perang besar, peristiwa penting, dan tokoh berpengaruh masa lalu.
                </p>
                <div class="flex items-center gap-3 pt-1 text-xs font-medium text-stone-500 dark:text-stone-400">
                    <a href="{{ route('feed') }}" class="inline-flex items-center gap-1.5 transition hover:text-stone-900 dark:hover:text-white">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 11a9 9 0 0 1 9 9"/><path d="M4 4a16 16 0 0 1 16 16"/><circle cx="5" cy="19" r="1"/></svg>
                        <span>RSS Feed</span>
                    </a>
                    <span class="text-stone-300 dark:text-stone-700">&middot;</span>
                    <a href="{{ route('sitemap') }}" class="transition hover:text-stone-900 dark:hover:text-white">Sitemap XML</a>
                </div>
            </div>

            {{-- Col 2: Navigasi Utama --}}
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-stone-400 dark:text-stone-500">Navigasi</p>
                <ul class="mt-4 space-y-2.5 text-xs font-medium">
                    <li>
                        <a href="/" class="text-stone-600 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Beranda</a>
                    </li>
                    <li>
                        <a href="{{ route('artikel.index') }}" class="text-stone-600 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Semua Artikel</a>
                    </li>
                    <li>
                        <a href="{{ route('kategori.index') }}" class="text-stone-600 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Indeks Kategori</a>
                    </li>
                    <li>
                        <a href="{{ route('tentang') }}" class="text-stone-600 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Tentang Redaksi</a>
                    </li>
                    <li>
                        <a href="{{ route('search') }}" class="text-stone-600 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">Pencarian Artikel</a>
                    </li>
                </ul>
            </div>

            {{-- Col 3: Kategori Terpopuler --}}
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-stone-400 dark:text-stone-500">Kategori Utama</p>
                <ul class="mt-4 space-y-2.5 text-xs font-medium">
                    @forelse ($footerKategoris ?? [] as $fKat)
                        <li>
                            <a href="{{ route('kategori.show', $fKat) }}" class="flex items-center justify-between text-stone-600 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">
                                <span class="truncate">{{ $fKat->nama }}</span>
                                <span class="font-mono text-[10px] text-stone-400 dark:text-stone-500">{{ $fKat->artikel_count }}</span>
                            </a>
                        </li>
                    @empty
                        <li class="text-stone-400 dark:text-stone-500">Belum ada kategori</li>
                    @endforelse
                </ul>
                <div class="mt-3.5 pt-2 border-t border-stone-200/50 dark:border-white/[0.04]">
                    <a href="{{ route('kategori.index') }}" class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#1e3a5f] transition hover:underline dark:text-stone-300 dark:hover:text-white">
                        Lihat semua kategori &rarr;
                    </a>
                </div>
            </div>

            {{-- Col 4: Linimasa Era --}}
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-stone-400 dark:text-stone-500">Linimasa Era</p>
                <ul class="mt-4 space-y-2.5 text-xs font-medium">
                    @forelse ($footerEras ?? [] as $fEra)
                        <li>
                            <a href="{{ route('era.show', $fEra) }}" class="flex items-center justify-between text-stone-600 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">
                                <span class="truncate">{{ $fEra->nama }}</span>
                                <span class="text-[10px] text-stone-400 dark:text-stone-500">{{ $fEra->periode }}</span>
                            </a>
                        </li>
                    @empty
                        <li class="text-stone-400 dark:text-stone-500">Belum ada era</li>
                    @endforelse
                </ul>
                <div class="mt-3.5 pt-2 border-t border-stone-200/50 dark:border-white/[0.04]">
                    <a href="/#perjalanan-waktu" class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#1e3a5f] transition hover:underline dark:text-stone-300 dark:hover:text-white">
                        Jelajahi linimasa &rarr;
                    </a>
                </div>
            </div>
        </div>

        {{-- Bottom Copyright & Legal --}}
        <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-stone-200/80 pt-8 text-xs text-stone-400 sm:flex-row dark:border-white/[0.06] dark:text-stone-500">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Look at History') }}. Seluruh hak cipta dilindungi.</p>
            <div class="flex items-center gap-5">
                <a href="{{ route('kontak') }}" class="transition hover:text-stone-600 dark:hover:text-stone-300">Kontak</a>
                <a href="{{ route('privasi') }}" class="transition hover:text-stone-600 dark:hover:text-stone-300">Kebijakan Privasi</a>
            </div>
        </div>
    </div>
</footer>
