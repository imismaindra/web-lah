@extends('layouts.admin')

@section('title', 'Manajemen Artikel')
@section('page-title', 'Artikel')

@section('content')
    <div class="space-y-6">
        {{-- Flash Message --}}
        @if (session('success'))
            <div id="flash-banner" class="flex items-center justify-between gap-3 rounded-2xl border border-emerald-500/20 bg-emerald-50/90 px-4 py-3.5 text-sm text-emerald-800 shadow-xs transition duration-200 dark:border-emerald-500/20 dark:bg-emerald-950/40 dark:text-emerald-300">
                <div class="flex items-center gap-3">
                    <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-xl bg-emerald-600/10 text-emerald-600 dark:bg-emerald-400/10 dark:text-emerald-400">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
                <button type="button" onclick="document.getElementById('flash-banner').remove()" class="rounded-lg p-1.5 text-emerald-600/70 transition hover:bg-emerald-200/50 hover:text-emerald-900 dark:text-emerald-400/70 dark:hover:bg-emerald-900/40 dark:hover:text-emerald-200" title="Tutup">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
        @endif

        {{-- Page Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="font-serif text-2xl font-bold tracking-tight text-stone-900 dark:text-stone-100">Daftar Artikel Sejarah</h2>
                    <span class="inline-flex items-center rounded-full bg-stone-100 px-2.5 py-0.5 text-xs font-semibold text-stone-600 dark:bg-white/[0.06] dark:text-stone-400">
                        {{ number_format($statusCounts['all'] ?? $artikels->total()) }} total
                    </span>
                </div>
                <p class="mt-1 text-sm text-stone-500 dark:text-stone-400">Kelola kurasi naskah, jadwal publikasi, dan pantau respons pembaca.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.artikel.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#1e3a5f] px-4 py-2.5 text-sm font-semibold text-white shadow-xs transition hover:bg-[#16304a] active:scale-[0.98] dark:bg-[#5b9bd5] dark:text-[#0f0f0e] dark:hover:bg-[#7ab3e0]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                    <span>Tulis Artikel Baru</span>
                </a>
            </div>
        </div>

        {{-- Metric Overview Strip --}}
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4">
            <div class="rounded-2xl border border-stone-200/70 bg-white p-4 transition dark:border-white/[0.06] dark:bg-[#171716]">
                <div class="flex items-center justify-between text-xs text-stone-500 dark:text-stone-400">
                    <span>Semua Naskah</span>
                    <svg class="h-4 w-4 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 6h10"/><path d="M6 10h10"/></svg>
                </div>
                <p class="mt-2 font-serif text-2xl font-bold tracking-tight text-stone-900 dark:text-stone-100">{{ number_format($statusCounts['all'] ?? 0) }}</p>
                <p class="mt-1 text-[11px] text-stone-400 dark:text-stone-500">Seluruh artikel tercatat</p>
            </div>

            <div class="rounded-2xl border border-stone-200/70 bg-white p-4 transition dark:border-white/[0.06] dark:bg-[#171716]">
                <div class="flex items-center justify-between text-xs text-emerald-600 dark:text-emerald-400">
                    <span>Diterbitkan</span>
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                </div>
                <p class="mt-2 font-serif text-2xl font-bold tracking-tight text-stone-900 dark:text-stone-100">{{ number_format($statusCounts['published'] ?? 0) }}</p>
                <p class="mt-1 text-[11px] text-stone-400 dark:text-stone-500">Tayang untuk publik</p>
            </div>

            <div class="rounded-2xl border border-stone-200/70 bg-white p-4 transition dark:border-white/[0.06] dark:bg-[#171716]">
                <div class="flex items-center justify-between text-xs text-sky-600 dark:text-sky-400">
                    <span>Draf & Jadwal</span>
                    <svg class="h-4 w-4 text-sky-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <p class="mt-2 font-serif text-2xl font-bold tracking-tight text-stone-900 dark:text-stone-100">{{ number_format(($statusCounts['draft'] ?? 0) + ($statusCounts['scheduled'] ?? 0)) }}</p>
                <p class="mt-1 text-[11px] text-stone-400 dark:text-stone-500">{{ $statusCounts['scheduled'] ?? 0 }} terjadwal, {{ $statusCounts['draft'] ?? 0 }} draf</p>
            </div>

            <div class="rounded-2xl border border-stone-200/70 bg-white p-4 transition dark:border-white/[0.06] dark:bg-[#171716]">
                <div class="flex items-center justify-between text-xs text-stone-500 dark:text-stone-400">
                    <span>Total Pembaca</span>
                    <svg class="h-4 w-4 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                </div>
                <p class="mt-2 font-serif text-2xl font-bold tracking-tight text-stone-900 dark:text-stone-100">{{ number_format($totalViews ?? 0) }}</p>
                <p class="mt-1 text-[11px] text-stone-400 dark:text-stone-500">Akumulasi tayangan artikel</p>
            </div>
        </div>

        {{-- Filters & Controls Shell --}}
        <div class="rounded-2xl border border-stone-200/80 bg-white p-4 shadow-2xs dark:border-white/[0.06] dark:bg-[#171716] sm:p-5">
            {{-- Status Tabs --}}
            @php
                $currentStatus = request('status');
                $tabs = [
                    '' => ['label' => 'Semua', 'count' => $statusCounts['all'] ?? $artikels->total()],
                    'published' => ['label' => 'Terbit', 'count' => $statusCounts['published'] ?? 0],
                    'draft' => ['label' => 'Draf', 'count' => $statusCounts['draft'] ?? 0],
                    'scheduled' => ['label' => 'Terjadwal', 'count' => $statusCounts['scheduled'] ?? 0],
                    'archived' => ['label' => 'Arsip', 'count' => $statusCounts['archived'] ?? 0],
                ];
            @endphp
            <div class="flex items-center gap-1.5 overflow-x-auto pb-3 border-b border-stone-100 dark:border-white/[0.06]">
                @foreach ($tabs as $key => $tab)
                    @php
                        $isActive = (string) $currentStatus === (string) $key;
                        $tabParams = array_filter(array_merge(request()->query(), ['status' => $key !== '' ? $key : null, 'page' => null]));
                        $tabUrl = route('admin.artikel.index', $tabParams);
                    @endphp
                    <a
                        href="{{ $tabUrl }}"
                        class="inline-flex items-center gap-2 whitespace-nowrap rounded-xl px-3.5 py-1.5 text-xs font-semibold transition active:scale-[0.98] {{ $isActive
                            ? 'bg-stone-900 text-white shadow-xs dark:bg-[#5b9bd5] dark:text-[#0f0f0e]'
                            : 'text-stone-600 hover:bg-stone-100 hover:text-stone-900 dark:text-stone-400 dark:hover:bg-white/[0.04] dark:hover:text-stone-200' }}"
                    >
                        <span>{{ $tab['label'] }}</span>
                        <span class="rounded-md px-1.5 py-0.2 text-[10px] font-bold {{ $isActive
                            ? 'bg-white/20 text-white dark:bg-[#0f0f0e]/20 dark:text-[#0f0f0e]'
                            : 'bg-stone-100 text-stone-500 dark:bg-white/[0.06] dark:text-stone-400' }}">
                            {{ $tab['count'] }}
                        </span>
                    </a>
                @endforeach
            </div>

            {{-- Search & Sub-filters Toolbar --}}
            <form id="filter-form" method="GET" action="{{ route('admin.artikel.index') }}" class="mt-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                @if (request()->filled('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif

                {{-- Left search bar --}}
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-stone-400 dark:text-stone-500">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </span>
                    <input
                        id="search-input"
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari judul, topik, atau kata kunci... (tekan / untuk fokus)"
                        class="w-full rounded-xl border border-stone-200 bg-stone-50/50 py-2.5 pl-10 pr-9 text-sm text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-[#1e3a5f] focus:bg-white focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-[#121211] dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10"
                    >
                    @if (request()->filled('search'))
                        <button
                            type="button"
                            onclick="document.getElementById('search-input').value=''; document.getElementById('filter-form').submit();"
                            class="absolute right-3 top-1/2 -translate-y-1/2 rounded-md p-1 text-stone-400 transition hover:bg-stone-200/70 hover:text-stone-700 dark:hover:bg-white/10 dark:hover:text-stone-200"
                            title="Hapus pencarian"
                        >
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        </button>
                    @endif
                </div>

                {{-- Right selectors & view switcher --}}
                <div class="flex flex-wrap items-center gap-2">
                    {{-- Category filter --}}
                    @if (isset($kategoris) && $kategoris->isNotEmpty())
                        <div class="relative">
                            <select
                                name="kategori"
                                onchange="document.getElementById('filter-form').submit()"
                                class="cursor-pointer appearance-none rounded-xl border border-stone-200 bg-stone-50/50 py-2.5 pl-3.5 pr-8 text-xs font-medium text-stone-700 outline-none transition hover:border-stone-300 focus:border-[#1e3a5f] focus:bg-white focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-[#121211] dark:text-stone-300 dark:focus:border-[#5b9bd5]"
                            >
                                <option value="">Semua Kategori</option>
                                @foreach ($kategoris as $kat)
                                    <option value="{{ $kat->id }}" {{ (string) request('kategori') === (string) $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <svg class="pointer-events-none absolute right-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    @endif

                    {{-- Sort filter --}}
                    <div class="relative">
                        <select
                            name="sort"
                            onchange="document.getElementById('filter-form').submit()"
                            class="cursor-pointer appearance-none rounded-xl border border-stone-200 bg-stone-50/50 py-2.5 pl-3.5 pr-8 text-xs font-medium text-stone-700 outline-none transition hover:border-stone-300 focus:border-[#1e3a5f] focus:bg-white focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-[#121211] dark:text-stone-300 dark:focus:border-[#5b9bd5]"
                        >
                            <option value="terbaru" {{ request('sort', 'terbaru') === 'terbaru' ? 'selected' : '' }}>Urut: Terbaru</option>
                            <option value="populer" {{ request('sort') === 'populer' ? 'selected' : '' }}>Urut: Paling Populer</option>
                            <option value="terlama" {{ request('sort') === 'terlama' ? 'selected' : '' }}>Urut: Terlama</option>
                        </select>
                        <svg class="pointer-events-none absolute right-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </div>

                    {{-- View mode toggle (Grid vs List) --}}
                    <div class="flex items-center rounded-xl border border-stone-200 bg-stone-50/50 p-1 dark:border-white/10 dark:bg-[#121211]">
                        <button
                            type="button"
                            id="view-grid-btn"
                            onclick="setArticleView('grid')"
                            class="rounded-lg p-1.5 transition text-stone-600 hover:text-stone-900 dark:text-stone-400 dark:hover:text-stone-200"
                            title="Tampilan Kartu (Grid)"
                        >
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                        </button>
                        <button
                            type="button"
                            id="view-list-btn"
                            onclick="setArticleView('list')"
                            class="rounded-lg p-1.5 transition text-stone-600 hover:text-stone-900 dark:text-stone-400 dark:hover:text-stone-200"
                            title="Tampilan Tabel (List)"
                        >
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/></svg>
                        </button>
                    </div>

                    {{-- Reset filter button if active --}}
                    @if (request()->hasAny(['search', 'status', 'kategori', 'sort']))
                        <a
                            href="{{ route('admin.artikel.index') }}"
                            class="inline-flex items-center gap-1.5 rounded-xl border border-stone-200 px-3 py-2 text-xs font-semibold text-stone-600 transition hover:bg-stone-100 hover:text-stone-900 dark:border-white/10 dark:text-stone-400 dark:hover:bg-white/[0.04] dark:hover:text-stone-200"
                            title="Reset semua filter"
                        >
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                            <span>Reset</span>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Main Article Display Area --}}
        <div>
            @if ($artikels->isEmpty())
                {{-- Empty state --}}
                <div class="rounded-3xl border border-dashed border-stone-300/80 bg-white p-12 text-center dark:border-white/10 dark:bg-[#171716]">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-stone-100 dark:bg-white/[0.04]">
                        <svg class="h-7 w-7 text-stone-400 dark:text-stone-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M10 2v4"/><path d="M14 2v4"/></svg>
                    </div>

                    @if (request()->hasAny(['search', 'status', 'kategori']))
                        <h3 class="mt-4 font-serif text-lg font-bold text-stone-800 dark:text-stone-200">Tidak ada artikel yang cocok</h3>
                        <p class="mx-auto mt-1 max-w-sm text-sm text-stone-500 dark:text-stone-400">Tidak ditemukan naskah dengan kriteria pencarian atau filter yang dipilih.</p>
                        <div class="mt-5">
                            <a href="{{ route('admin.artikel.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-stone-100 px-4 py-2 text-xs font-semibold text-stone-700 transition hover:bg-stone-200 dark:bg-white/[0.06] dark:text-stone-300 dark:hover:bg-white/10">
                                Bersihkan Filter
                            </a>
                        </div>
                    @else
                        <h3 class="mt-4 font-serif text-lg font-bold text-stone-800 dark:text-stone-200">Belum ada artikel sejarah</h3>
                        <p class="mx-auto mt-1 max-w-sm text-sm text-stone-500 dark:text-stone-400">Mulai tulis karya dan kajian sejarah pertama Anda untuk disajikan kepada pembaca.</p>
                        <div class="mt-5">
                            <a href="{{ route('admin.artikel.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#1e3a5f] px-4 py-2.5 text-xs font-semibold text-white shadow-xs transition hover:bg-[#16304a] dark:bg-[#5b9bd5] dark:text-[#0f0f0e] dark:hover:bg-[#7ab3e0]">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                                <span>Tulis Artikel Baru</span>
                            </a>
                        </div>
                    @endif
                </div>
            @else
                {{-- 1. GRID VIEW --}}
                <div id="articles-grid-view" class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($artikels as $artikel)
                        <div class="group relative flex flex-col justify-between overflow-hidden rounded-2xl border border-stone-200/80 bg-white shadow-2xs transition duration-200 hover:-translate-y-0.5 hover:border-stone-300 hover:shadow-sm dark:border-white/[0.07] dark:bg-[#171716] dark:hover:border-white/15 dark:hover:shadow-black/40">
                            {{-- Top Header: Media Cover & Status Badges --}}
                            <div>
                                <div class="aspect-[16/10] relative w-full overflow-hidden bg-stone-100 dark:bg-white/[0.02]">
                                    @if ($artikel->gambar)
                                        <img
                                            src="{{ asset('storage/' . $artikel->gambar) }}"
                                            alt="{{ $artikel->judul }}"
                                            class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                            loading="lazy"
                                        >
                                    @else
                                        <div class="flex h-full w-full flex-col items-center justify-center bg-radial from-stone-100 to-stone-200/50 p-6 text-stone-300 dark:from-[#1b1b1a] dark:to-[#141413] dark:text-stone-600">
                                            <svg class="h-10 w-10 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                            <span class="mt-1 text-[11px] font-medium tracking-wide">Ilustrasi Naskah</span>
                                        </div>
                                    @endif

                                    {{-- Soft gradient scrim --}}
                                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20"></div>

                                    {{-- Top Badges: Status (Left) & Category (Right) --}}
                                    <div class="absolute inset-x-3 top-3 flex items-center justify-between gap-2">
                                        <div class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-[11px] font-bold backdrop-blur-md shadow-xs
                                            {{ match($artikel->status) {
                                                'published' => 'bg-emerald-950/80 text-emerald-300 ring-1 ring-emerald-500/30',
                                                'scheduled' => 'bg-sky-950/80 text-sky-300 ring-1 ring-sky-500/30',
                                                'archived' => 'bg-amber-950/80 text-amber-300 ring-1 ring-amber-500/30',
                                                default => 'bg-stone-900/80 text-stone-300 ring-1 ring-stone-700/50',
                                            } }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ match($artikel->status) {
                                                'published' => 'bg-emerald-400',
                                                'scheduled' => 'bg-sky-400',
                                                'archived' => 'bg-amber-400',
                                                default => 'bg-stone-400',
                                            } }}"></span>
                                            <span>
                                                {{ match($artikel->status) {
                                                    'published' => 'Terbit',
                                                    'scheduled' => 'Terjadwal',
                                                    'archived' => 'Arsip',
                                                    default => 'Draf',
                                                } }}
                                            </span>
                                        </div>

                                        @if ($artikel->kategori)
                                            <span class="truncate rounded-lg bg-black/60 px-2.5 py-1 text-[11px] font-semibold text-white/90 backdrop-blur-md ring-1 ring-white/10">
                                                {{ $artikel->kategori->nama }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Bottom overlay label if scheduled --}}
                                    @if ($artikel->status === 'scheduled' && $artikel->published_at)
                                        <div class="absolute bottom-2 left-3 right-3 flex items-center gap-1.5 rounded-md bg-sky-950/90 px-2.5 py-1 text-[11px] font-medium text-sky-200 backdrop-blur-md">
                                            <svg class="h-3.5 w-3.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                            <span class="truncate">Tayang: {{ $artikel->published_at->format('d M Y, H:i') }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Card Content --}}
                                <div class="p-5">
                                    {{-- Metadata line --}}
                                    <div class="flex items-center gap-2 text-xs text-stone-400 dark:text-stone-500">
                                        <span>{{ ($artikel->published_at ?? $artikel->created_at)->format('d M Y') }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $artikel->reading_time ?? 1 }} mnt baca</span>
                                        @if ($artikel->author)
                                            <span>&bull;</span>
                                            <span class="truncate max-w-[120px]">{{ $artikel->author->penulis->nama_pena ?? $artikel->author->name }}</span>
                                        @endif
                                    </div>

                                    {{-- Title --}}
                                    <h3 class="mt-2 line-clamp-2 font-serif text-lg font-bold leading-snug text-stone-900 transition group-hover:text-[#1e3a5f] dark:text-stone-100 dark:group-hover:text-[#5b9bd5]">
                                        <a href="{{ route('admin.artikel.edit', $artikel) }}">
                                            {{ $artikel->judul }}
                                        </a>
                                    </h3>

                                    {{-- Excerpt --}}
                                    @if ($artikel->ringkasan)
                                        <p class="mt-2 line-clamp-2 text-xs leading-relaxed text-stone-500 dark:text-stone-400">
                                            {{ $artikel->ringkasan }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Card Footer Actions --}}
                            <div class="border-t border-stone-100 bg-stone-50/60 px-5 py-3 dark:border-white/[0.04] dark:bg-white/[0.01]">
                                <div class="flex items-center justify-between">
                                    {{-- Views indicator --}}
                                    <div class="inline-flex items-center gap-1.5 text-xs font-medium text-stone-500 dark:text-stone-400">
                                        <svg class="h-3.5 w-3.5 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                        <span>{{ number_format($artikel->views) }} pembaca</span>
                                    </div>

                                    {{-- Action buttons --}}
                                    <div class="flex items-center gap-1">
                                        <a
                                            href="{{ route('admin.artikel.preview', $artikel) }}"
                                            target="_blank"
                                            rel="noopener"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-stone-200/80 bg-white text-stone-600 transition hover:border-stone-300 hover:bg-stone-100 hover:text-[#1e3a5f] active:scale-95 dark:border-white/10 dark:bg-[#1a1a19] dark:text-stone-300 dark:hover:bg-white/10 dark:hover:text-[#5b9bd5]"
                                            title="Pratinjau Artikel"
                                        >
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                                        </a>

                                        <a
                                            href="{{ route('admin.artikel.edit', $artikel) }}"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-stone-200/80 bg-white text-stone-600 transition hover:border-stone-300 hover:bg-stone-100 hover:text-[#1e3a5f] active:scale-95 dark:border-white/10 dark:bg-[#1a1a19] dark:text-stone-300 dark:hover:bg-white/10 dark:hover:text-[#5b9bd5]"
                                            title="Edit Artikel"
                                        >
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                        </a>

                                        <form method="POST" action="{{ route('admin.artikel.destroy', $artikel) }}" onsubmit="return confirm('Hapus artikel sejarah ini secara permanen?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-stone-200/80 bg-white text-stone-600 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600 active:scale-95 dark:border-white/10 dark:bg-[#1a1a19] dark:text-stone-300 dark:hover:border-red-500/30 dark:hover:bg-red-500/10 dark:hover:text-red-400"
                                                title="Hapus Artikel"
                                            >
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- 2. LIST / TABLE VIEW --}}
                <div id="articles-list-view" class="hidden overflow-hidden rounded-2xl border border-stone-200/80 bg-white shadow-2xs dark:border-white/[0.07] dark:bg-[#171716]">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-stone-200/80 bg-stone-50/80 text-xs font-semibold text-stone-500 dark:border-white/[0.06] dark:bg-white/[0.02] dark:text-stone-400">
                                <tr>
                                    <th class="py-3.5 pl-5 pr-3">Artikel</th>
                                    <th class="px-3 py-3.5">Kategori</th>
                                    <th class="px-3 py-3.5">Penulis</th>
                                    <th class="px-3 py-3.5">Status</th>
                                    <th class="px-3 py-3.5">Performa</th>
                                    <th class="px-3 py-3.5">Tanggal</th>
                                    <th class="py-3.5 pl-3 pr-5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100 dark:divide-white/[0.04]">
                                @foreach ($artikels as $artikel)
                                    <tr class="transition hover:bg-stone-50/70 dark:hover:bg-white/[0.02]">
                                        {{-- Article thumbnail + Title --}}
                                        <td class="py-3 pl-5 pr-3">
                                            <div class="flex items-center gap-3">
                                                <div class="h-12 w-16 flex-shrink-0 overflow-hidden rounded-xl bg-stone-100 dark:bg-white/[0.03]">
                                                    @if ($artikel->gambar)
                                                        <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}" class="h-full w-full object-cover">
                                                    @else
                                                        <div class="flex h-full w-full items-center justify-center text-stone-300 dark:text-stone-600">
                                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3-3a2 2 0 0 0-3 0l-5 5"/></svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="min-w-0 max-w-md">
                                                    <a href="{{ route('admin.artikel.edit', $artikel) }}" class="line-clamp-1 font-serif text-sm font-bold text-stone-900 transition hover:text-[#1e3a5f] dark:text-stone-100 dark:hover:text-[#5b9bd5]">
                                                        {{ $artikel->judul }}
                                                    </a>
                                                    @if ($artikel->ringkasan)
                                                        <p class="mt-0.5 line-clamp-1 text-xs text-stone-400 dark:text-stone-500">{{ $artikel->ringkasan }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Category --}}
                                        <td class="whitespace-nowrap px-3 py-3 text-xs text-stone-600 dark:text-stone-300">
                                            <span class="rounded-md bg-stone-100 px-2 py-1 font-medium dark:bg-white/[0.05]">
                                                {{ $artikel->kategori->nama ?? '-' }}
                                            </span>
                                        </td>

                                        {{-- Author --}}
                                        <td class="whitespace-nowrap px-3 py-3 text-xs text-stone-600 dark:text-stone-300">
                                            {{ $artikel->author->penulis->nama_pena ?? $artikel->author->name ?? 'Admin' }}
                                        </td>

                                        {{-- Status --}}
                                        <td class="whitespace-nowrap px-3 py-3">
                                            <span class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1 text-[11px] font-semibold
                                                {{ match($artikel->status) {
                                                    'published' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
                                                    'scheduled' => 'bg-sky-50 text-sky-700 dark:bg-sky-500/10 dark:text-sky-400',
                                                    'archived' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                                                    default => 'bg-stone-100 text-stone-600 dark:bg-white/[0.05] dark:text-stone-400',
                                                } }}">
                                                <span class="h-1.5 w-1.5 rounded-full {{ match($artikel->status) {
                                                    'published' => 'bg-emerald-500',
                                                    'scheduled' => 'bg-sky-500',
                                                    'archived' => 'bg-amber-500',
                                                    default => 'bg-stone-400',
                                                } }}"></span>
                                                <span>
                                                    {{ match($artikel->status) {
                                                        'published' => 'Terbit',
                                                        'scheduled' => 'Terjadwal',
                                                        'archived' => 'Arsip',
                                                        default => 'Draf',
                                                    } }}
                                                </span>
                                            </span>
                                        </td>

                                        {{-- Performance --}}
                                        <td class="whitespace-nowrap px-3 py-3 text-xs text-stone-500 dark:text-stone-400">
                                            <div class="flex items-center gap-3">
                                                <span class="inline-flex items-center gap-1 font-medium">
                                                    <svg class="h-3.5 w-3.5 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                                    {{ number_format($artikel->views) }}
                                                </span>
                                                <span>{{ $artikel->reading_time ?? 1 }} mnt</span>
                                            </div>
                                        </td>

                                        {{-- Date --}}
                                        <td class="whitespace-nowrap px-3 py-3 text-xs text-stone-500 dark:text-stone-400">
                                            @if ($artikel->status === 'scheduled' && $artikel->published_at)
                                                <span class="font-medium text-sky-600 dark:text-sky-400">{{ $artikel->published_at->format('d M H:i') }}</span>
                                            @else
                                                <span>{{ ($artikel->published_at ?? $artikel->created_at)->format('d M Y') }}</span>
                                            @endif
                                        </td>

                                        {{-- Action Buttons --}}
                                        <td class="whitespace-nowrap py-3 pl-3 pr-5 text-right">
                                            <div class="inline-flex items-center gap-1">
                                                <a
                                                    href="{{ route('admin.artikel.preview', $artikel) }}"
                                                    target="_blank"
                                                    rel="noopener"
                                                    class="rounded-lg p-1.5 text-stone-400 transition hover:bg-stone-100 hover:text-[#1e3a5f] dark:hover:bg-white/[0.05] dark:hover:text-[#5b9bd5]"
                                                    title="Pratinjau"
                                                >
                                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                                                </a>

                                                <a
                                                    href="{{ route('admin.artikel.edit', $artikel) }}"
                                                    class="rounded-lg p-1.5 text-stone-400 transition hover:bg-stone-100 hover:text-[#1e3a5f] dark:hover:bg-white/[0.05] dark:hover:text-[#5b9bd5]"
                                                    title="Edit"
                                                >
                                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                                </a>

                                                <form method="POST" action="{{ route('admin.artikel.destroy', $artikel) }}" onsubmit="return confirm('Hapus artikel sejarah ini secara permanen?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="rounded-lg p-1.5 text-stone-400 transition hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10 dark:hover:text-red-400"
                                                        title="Hapus"
                                                    >
                                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="mt-8 flex items-center justify-between">
                    <p class="text-xs text-stone-500 dark:text-stone-400">
                        Menampilkan {{ $artikels->firstItem() ?? 0 }} sampai {{ $artikels->lastItem() ?? 0 }} dari {{ $artikels->total() }} naskah
                    </p>
                    <div>
                        {{ $artikels->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function setArticleView(mode) {
                const gridView = document.getElementById('articles-grid-view');
                const listView = document.getElementById('articles-list-view');
                const gridBtn = document.getElementById('view-grid-btn');
                const listBtn = document.getElementById('view-list-btn');

                if (!gridView || !listView) return;

                if (mode === 'list') {
                    gridView.classList.add('hidden');
                    listView.classList.remove('hidden');

                    listBtn?.classList.add('bg-white', 'text-stone-900', 'shadow-xs', 'dark:bg-stone-700', 'dark:text-white');
                    listBtn?.classList.remove('text-stone-600', 'dark:text-stone-400');

                    gridBtn?.classList.remove('bg-white', 'text-stone-900', 'shadow-xs', 'dark:bg-stone-700', 'dark:text-white');
                    gridBtn?.classList.add('text-stone-600', 'dark:text-stone-400');

                    localStorage.setItem('lah_article_view_mode', 'list');
                } else {
                    listView.classList.add('hidden');
                    gridView.classList.remove('hidden');

                    gridBtn?.classList.add('bg-white', 'text-stone-900', 'shadow-xs', 'dark:bg-stone-700', 'dark:text-white');
                    gridBtn?.classList.remove('text-stone-600', 'dark:text-stone-400');

                    listBtn?.classList.remove('bg-white', 'text-stone-900', 'shadow-xs', 'dark:bg-stone-700', 'dark:text-white');
                    listBtn?.classList.add('text-stone-600', 'dark:text-stone-400');

                    localStorage.setItem('lah_article_view_mode', 'grid');
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                const savedView = localStorage.getItem('lah_article_view_mode') || 'grid';
                setArticleView(savedView);

                // Quick keyboard shortcut "/" for search focus
                document.addEventListener('keydown', (e) => {
                    if (e.key === '/' && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
                        e.preventDefault();
                        const searchInput = document.getElementById('search-input');
                        if (searchInput) {
                            searchInput.focus();
                            searchInput.select();
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
