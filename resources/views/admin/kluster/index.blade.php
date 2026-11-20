@extends('layouts.admin')

@section('title', 'Kluster Topik & Visualisasi Linking')
@section('page-title', 'Kluster & Linking')

@section('content')
    <div class="space-y-6">
        @if (session('success'))
            <div id="flash-banner" class="flex items-center justify-between gap-3 rounded-2xl border border-emerald-500/20 bg-emerald-50/90 px-4 py-3.5 text-sm text-emerald-800 shadow-xs dark:border-emerald-500/20 dark:bg-emerald-950/40 dark:text-emerald-300">
                <div class="flex items-center gap-3">
                    <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-xl bg-emerald-600/10 text-emerald-600 dark:bg-emerald-400/10 dark:text-emerald-400">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
                <button type="button" onclick="document.getElementById('flash-banner').remove()" class="rounded-lg p-1.5 text-emerald-600/70 transition hover:bg-emerald-200/50 hover:text-emerald-900 dark:text-emerald-400/70 dark:hover:bg-emerald-900/40 dark:hover:text-emerald-200">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
        @endif

        {{-- Page Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="font-serif text-2xl font-bold tracking-tight text-stone-900 dark:text-stone-100">Arsitektur Kluster & Jalur Tautan</h2>
                    <span class="inline-flex items-center rounded-full bg-stone-100 px-2.5 py-0.5 text-xs font-semibold text-stone-600 dark:bg-white/[0.06] dark:text-stone-400">
                        {{ $audit['total_pillars'] }} Pilar &bull; {{ $audit['total_supporting'] }} Turunan
                    </span>
                </div>
                <p class="mt-1 text-sm text-stone-500 dark:text-stone-400">Visualisasi relasi pilar-pendukung dan pantauan validasi tautan balik demi otoritas SEO topik.</p>
            </div>
            <div class="flex items-center gap-3">
                <form method="POST" action="{{ route('admin.kluster.sync') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 bg-white px-3.5 py-2.5 text-xs font-semibold text-stone-700 shadow-xs transition hover:bg-stone-50 active:scale-[0.98] dark:border-white/10 dark:bg-[#171716] dark:text-stone-300 dark:hover:bg-white/[0.04]">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                        <span>Sinkronkan Jalur Tautan</span>
                    </button>
                </form>
                <a href="{{ route('admin.artikel.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#1e3a5f] px-4 py-2.5 text-xs font-semibold text-white shadow-xs transition hover:bg-[#16304a] active:scale-[0.98] dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                    <span>Tulis Artikel</span>
                </a>
            </div>
        </div>

        {{-- Metrics Overview --}}
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4">
            <div class="rounded-2xl border border-stone-200/80 bg-white p-4 transition dark:border-white/[0.06] dark:bg-[#171716]">
                <div class="flex items-center justify-between text-xs text-stone-500 dark:text-stone-400">
                    <span>Artikel Pilar</span>
                    <span class="inline-flex h-2 w-2 rounded-full bg-[#1e3a5f] dark:bg-sky-400"></span>
                </div>
                <p class="mt-2 font-serif text-2xl font-bold tracking-tight text-stone-900 dark:text-stone-100">{{ $audit['total_pillars'] }}</p>
                <p class="mt-1 text-[11px] text-stone-400 dark:text-stone-500">Halaman rujukan utama topik</p>
            </div>

            <div class="rounded-2xl border border-stone-200/80 bg-white p-4 transition dark:border-white/[0.06] dark:bg-[#171716]">
                <div class="flex items-center justify-between text-xs text-stone-500 dark:text-stone-400">
                    <span>Artikel Pendukung</span>
                    <svg class="h-4 w-4 text-stone-400 dark:text-stone-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                </div>
                <p class="mt-2 font-serif text-2xl font-bold tracking-tight text-stone-900 dark:text-stone-100">{{ $audit['total_supporting'] }}</p>
                <p class="mt-1 text-[11px] text-stone-400 dark:text-stone-500">Kajian spesifik turunan</p>
            </div>

            <div class="rounded-2xl border {{ $audit['missing_backlink_count'] > 0 ? 'border-amber-400/70 bg-amber-50/50 dark:border-amber-500/30 dark:bg-amber-950/20' : 'border-stone-200/80 bg-white dark:border-white/[0.06] dark:bg-[#171716]' }} p-4 transition">
                <div class="flex items-center justify-between text-xs {{ $audit['missing_backlink_count'] > 0 ? 'text-amber-700 dark:text-amber-400' : 'text-stone-500 dark:text-stone-400' }}">
                    <span>Turunan Tanpa Tautan Pilar</span>
                    @if ($audit['missing_backlink_count'] > 0)
                        <span class="inline-flex h-2 w-2 rounded-full bg-amber-500 animate-pulse"></span>
                    @else
                        <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                    @endif
                </div>
                <p class="mt-2 font-serif text-2xl font-bold tracking-tight {{ $audit['missing_backlink_count'] > 0 ? 'text-amber-900 dark:text-amber-200' : 'text-stone-900 dark:text-stone-100' }}">
                    {{ $audit['missing_backlink_count'] }}
                </p>
                <p class="mt-1 text-[11px] {{ $audit['missing_backlink_count'] > 0 ? 'text-amber-700 dark:text-amber-400 font-medium' : 'text-stone-400 dark:text-stone-500' }}">
                    {{ $audit['missing_backlink_count'] > 0 ? 'Perlu tautan balik ke pilar' : 'Seluruh turunan terhubung aman' }}
                </p>
            </div>

            <div class="rounded-2xl border border-stone-200/80 bg-white p-4 transition dark:border-white/[0.06] dark:bg-[#171716]">
                <div class="flex items-center justify-between text-xs text-emerald-600 dark:text-emerald-400">
                    <span>Tautan Sehat</span>
                    <svg class="h-4 w-4 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
                </div>
                <p class="mt-2 font-serif text-2xl font-bold tracking-tight text-stone-900 dark:text-stone-100">{{ $audit['healthy_count'] }}</p>
                <p class="mt-1 text-[11px] text-stone-400 dark:text-stone-500">Turunan dengan tautan balik aktif</p>
            </div>
        </div>

        {{-- Alert: Problematic Supporting Articles (Missing Pillar Backlink) --}}
        @if ($audit['missing_backlink_count'] > 0)
            <div id="audit-alert-section" class="overflow-hidden rounded-2xl border border-amber-300 bg-amber-50/90 dark:border-amber-500/20 dark:bg-amber-950/25">
                <div class="border-b border-amber-200/80 px-5 py-4 dark:border-amber-500/20">
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-500/15 text-amber-700 dark:bg-amber-400/10 dark:text-amber-400">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            </span>
                            <h3 class="font-serif text-base font-bold text-amber-950 dark:text-amber-200">
                                Validasi Kluster: {{ $audit['missing_backlink_count'] }} Artikel Turunan Belum Menautkan Balik ke Pilar
                            </h3>
                        </div>
                        <span class="text-xs text-amber-800/80 dark:text-amber-300/80">Wajib ditautkan agar hierarki kluster topik efektif</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="border-b border-amber-200/60 bg-amber-100/50 font-semibold text-amber-900 dark:border-amber-500/10 dark:bg-amber-900/20 dark:text-amber-200">
                            <tr>
                                <th class="py-2.5 pl-5 pr-3">Artikel Turunan</th>
                                <th class="px-3 py-2.5">Pilar Induk Terkait</th>
                                <th class="px-3 py-2.5">Status Naskah</th>
                                <th class="py-2.5 pl-3 pr-5 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-amber-200/40 dark:divide-amber-500/10">
                            @foreach ($audit['missing_backlinks'] as $item)
                                <tr class="transition hover:bg-amber-100/30 dark:hover:bg-amber-900/10">
                                    <td class="py-3 pl-5 pr-3 font-medium text-stone-900 dark:text-stone-100">
                                        <div class="line-clamp-1 max-w-md font-serif text-sm">
                                            {{ $item->judul }}
                                        </div>
                                        <div class="mt-0.5 text-[11px] text-stone-500 dark:text-stone-400">
                                            /artikel/{{ $item->slug }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-3 text-stone-700 dark:text-stone-300">
                                        <span class="inline-flex items-center gap-1.5 rounded-md bg-white/80 px-2.5 py-1 text-xs font-semibold text-stone-800 shadow-2xs dark:bg-white/[0.06] dark:text-stone-200">
                                            <span class="h-1.5 w-1.5 rounded-full bg-[#1e3a5f] dark:bg-sky-400"></span>
                                            {{ $item->pillar->judul ?? 'Pilar #' . $item->pillar_id }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-3">
                                        <span class="inline-flex rounded-md bg-stone-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-stone-600 dark:bg-white/[0.06] dark:text-stone-400">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap py-3 pl-3 pr-5 text-right">
                                        <a
                                            href="{{ route('admin.artikel.edit', $item) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-[#1e3a5f] px-3 py-1.5 text-xs font-semibold text-white shadow-2xs transition hover:bg-[#16304a] dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200"
                                        >
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                                            <span>Edit & Tautkan Sekarang</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Interactive Visualizer & Inspector Shell --}}
        <div class="rounded-2xl border border-stone-200/80 bg-white p-4 shadow-2xs dark:border-white/[0.06] dark:bg-[#171716] sm:p-5">
            {{-- Visualization Controls --}}
            <div class="flex flex-col gap-3 pb-4 border-b border-stone-100 dark:border-white/[0.06] sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-wrap items-center gap-2.5">
                    {{-- Filter Pillar Dropdown --}}
                    <div class="relative">
                        <select id="select-pillar-filter" class="cursor-pointer appearance-none rounded-xl border border-stone-200 bg-stone-50/70 py-2 pl-3.5 pr-8 text-xs font-medium text-stone-700 outline-none transition hover:border-stone-300 focus:border-[#1e3a5f] focus:bg-white dark:border-white/10 dark:bg-[#121211] dark:text-stone-300">
                            <option value="">Semua Kluster Topik</option>
                            @foreach ($pillars as $pil)
                                <option value="{{ $pil->id }}">Kluster: {{ $pil->judul }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-stone-400 dark:text-stone-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                    </div>

                    {{-- Toggle Issues Only --}}
                    <label class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-stone-200 bg-stone-50/70 px-3 py-2 text-xs font-medium text-stone-700 transition hover:bg-stone-100 dark:border-white/10 dark:bg-[#121211] dark:text-stone-300 dark:hover:bg-white/[0.04]">
                        <input type="checkbox" id="toggle-issues-only" class="rounded border-stone-300 text-[#1e3a5f] focus:ring-0 dark:border-stone-600">
                        <span>Hanya Tampilkan Masalah</span>
                    </label>

                    {{-- Search Node --}}
                    <div class="relative">
                        <input
                            type="text"
                            id="search-node-input"
                            placeholder="Cari artikel di peta..."
                            class="w-44 rounded-xl border border-stone-200 bg-stone-50/70 py-2 pl-8 pr-3 text-xs outline-none transition focus:w-56 focus:border-[#1e3a5f] focus:bg-white dark:border-white/10 dark:bg-[#121211] dark:text-stone-200"
                        >
                        <svg class="pointer-events-none absolute left-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </div>
                </div>

                {{-- Zoom / Reset Controls --}}
                <div class="flex items-center gap-1.5 self-end sm:self-auto">
                    <button type="button" id="btn-zoom-in" class="rounded-lg border border-stone-200 bg-stone-50/70 p-2 text-stone-600 transition hover:bg-stone-100 dark:border-white/10 dark:bg-[#121211] dark:text-stone-300" title="Perbesar">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                    </button>
                    <button type="button" id="btn-zoom-out" class="rounded-lg border border-stone-200 bg-stone-50/70 p-2 text-stone-600 transition hover:bg-stone-100 dark:border-white/10 dark:bg-[#121211] dark:text-stone-300" title="Perkecil">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                    </button>
                    <button type="button" id="btn-zoom-reset" class="rounded-lg border border-stone-200 bg-stone-50/70 px-2.5 py-2 text-xs font-medium text-stone-600 transition hover:bg-stone-100 dark:border-white/10 dark:bg-[#121211] dark:text-stone-300" title="Reset Tampilan">
                        Reset
                    </button>
                </div>
            </div>

            {{-- Legend Strip --}}
            <div class="flex flex-wrap items-center gap-4 py-2.5 text-[11px] text-stone-500 dark:text-stone-400">
                <span class="font-semibold text-stone-700 dark:text-stone-300">Petunjuk Node:</span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="h-3 w-3 rounded-full bg-[#1e3a5f] ring-2 ring-[#1e3a5f]/20"></span>
                    <span>Artikel Pilar (Hub)</span>
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="h-3 w-3 rounded-full bg-emerald-600 ring-2 ring-emerald-500/20"></span>
                    <span>Turunan (Tautan Balik Aman)</span>
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="h-3 w-3 rounded-full bg-amber-500 ring-2 ring-amber-500/30"></span>
                    <span>Turunan (Belum Tautkan Pilar)</span>
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="h-3 w-3 rounded-full bg-stone-400"></span>
                    <span>Artikel Standar</span>
                </span>
                <span class="border-l border-stone-200 pl-3 dark:border-white/10">Klik node untuk detail inspeksi & aksi cepat.</span>
            </div>

            {{-- Visual Canvas & Inspector Layout --}}
            <div class="relative grid grid-cols-1 gap-4 lg:grid-cols-4">
                {{-- SVG Graph Area --}}
                <div class="lg:col-span-3 relative h-[560px] overflow-hidden rounded-xl border border-stone-200/80 bg-stone-50/40 dark:border-white/[0.06] dark:bg-[#111110]">
                    <svg id="cluster-svg" class="h-full w-full cursor-grab active:cursor-grabbing select-none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <marker id="arrow-green" viewBox="0 0 10 10" refX="16" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                                <path d="M 0 0 L 10 5 L 0 10 z" fill="#059669" />
                            </marker>
                            <marker id="arrow-amber" viewBox="0 0 10 10" refX="16" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                                <path d="M 0 0 L 10 5 L 0 10 z" fill="#d97706" />
                            </marker>
                            <marker id="arrow-blue" viewBox="0 0 10 10" refX="16" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                                <path d="M 0 0 L 10 5 L 0 10 z" fill="#1e3a5f" />
                            </marker>
                            <marker id="arrow-stone" viewBox="0 0 10 10" refX="16" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                                <path d="M 0 0 L 10 5 L 0 10 z" fill="#a8a29e" />
                            </marker>
                        </defs>
                        <g id="svg-viewport">
                            <g id="svg-edges"></g>
                            <g id="svg-nodes"></g>
                        </g>
                    </svg>

                    {{-- Empty/Loading overlay --}}
                    <div id="graph-loading" class="absolute inset-0 flex items-center justify-center bg-white/70 backdrop-blur-2xs dark:bg-black/50 hidden">
                        <div class="flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-xs font-semibold text-stone-700 shadow-md dark:bg-stone-800 dark:text-stone-200">
                            <svg class="h-4 w-4 animate-spin text-[#1e3a5f]" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10" stroke-width="4" class="opacity-25"/><path d="M4 12a8 8 0 0 1 8-8" stroke-width="4" class="opacity-75"/></svg>
                            <span>Memuat jalur linking...</span>
                        </div>
                    </div>
                </div>

                {{-- Node Inspector Sidebar --}}
                <div class="lg:col-span-1 rounded-xl border border-stone-200/80 bg-white p-4 dark:border-white/[0.06] dark:bg-[#171716] flex flex-col justify-between">
                    <div id="inspector-content" class="space-y-4">
                        <div class="border-b border-stone-100 pb-3 dark:border-white/[0.06]">
                            <span id="insp-type-badge" class="inline-flex rounded-md bg-stone-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-stone-600 dark:bg-white/[0.06] dark:text-stone-300">
                                Pilih Node
                            </span>
                            <h4 id="insp-title" class="mt-2 font-serif text-base font-bold text-stone-900 line-clamp-2 dark:text-stone-100">
                                Klik artikel pada grafik untuk inspeksi jalur
                            </h4>
                            <p id="insp-slug" class="mt-0.5 text-[11px] text-stone-400 font-mono line-clamp-1"></p>
                        </div>

                        <div class="space-y-3 text-xs">
                            <div>
                                <span class="text-stone-400 block text-[11px]">Kategori:</span>
                                <span id="insp-category" class="font-medium text-stone-700 dark:text-stone-300">-</span>
                            </div>

                            <div id="insp-pillar-box" class="rounded-lg border border-stone-200 bg-stone-50/70 p-3 dark:border-white/10 dark:bg-[#121211]">
                                <span class="text-[11px] text-stone-500 font-medium block">Pilar Induk Terkait:</span>
                                <p id="insp-pillar-title" class="font-serif font-bold text-stone-800 dark:text-stone-200 mt-1">-</p>
                                <div id="insp-pillar-status" class="mt-2"></div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-center">
                                <div class="rounded-lg border border-stone-100 bg-stone-50/50 p-2 dark:border-white/[0.04] dark:bg-white/[0.02]">
                                    <span class="text-[10px] text-stone-400 uppercase font-semibold">Tautan Keluar</span>
                                    <p id="insp-outgoing-count" class="font-serif text-lg font-bold text-stone-800 dark:text-stone-200 mt-0.5">0</p>
                                </div>
                                <div class="rounded-lg border border-stone-100 bg-stone-50/50 p-2 dark:border-white/[0.04] dark:bg-white/[0.02]">
                                    <span class="text-[10px] text-stone-400 uppercase font-semibold">Status</span>
                                    <p id="insp-status" class="font-medium text-xs text-stone-700 dark:text-stone-300 mt-1 capitalize">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="insp-actions" class="mt-5 space-y-2 border-t border-stone-100 pt-3 dark:border-white/[0.06] hidden">
                        <a id="btn-insp-edit" href="#" class="flex w-full items-center justify-center gap-1.5 rounded-xl bg-[#1e3a5f] px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-[#16304a] dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                            <span>Edit & Atur Tautan</span>
                        </a>
                        <a id="btn-insp-view" href="#" target="_blank" rel="noopener" class="flex w-full items-center justify-center gap-1.5 rounded-xl border border-stone-200 bg-white px-3.5 py-2 text-xs font-semibold text-stone-700 transition hover:bg-stone-50 dark:border-white/10 dark:bg-white/[0.04] dark:text-stone-300">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            <span>Buka Artikel Web</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const svg = document.getElementById('cluster-svg');
        const viewport = document.getElementById('svg-viewport');
        const edgesLayer = document.getElementById('svg-edges');
        const nodesLayer = document.getElementById('svg-nodes');
        const selectPillar = document.getElementById('select-pillar-filter');
        const toggleIssues = document.getElementById('toggle-issues-only');
        const searchInput = document.getElementById('search-node-input');
        const loadingOverlay = document.getElementById('graph-loading');

        let graphData = { nodes: [], edges: [] };
        let scale = 1;
        let panX = 0;
        let panY = 0;
        let isDragging = false;
        let startX, startY;

        function updateTransform() {
            viewport.setAttribute('transform', `translate(${panX}, ${panY}) scale(${scale})`);
        }

        // Zoom & Pan listeners
        svg.addEventListener('mousedown', (e) => {
            if (e.target.closest('.graph-node')) return;
            isDragging = true;
            startX = e.clientX - panX;
            startY = e.clientY - panY;
        });

        window.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            panX = e.clientX - startX;
            panY = e.clientY - startY;
            updateTransform();
        });

        window.addEventListener('mouseup', () => { isDragging = false; });

        svg.addEventListener('wheel', (e) => {
            e.preventDefault();
            const zoomFactor = e.deltaY < 0 ? 1.1 : 0.9;
            const newScale = Math.min(Math.max(scale * zoomFactor, 0.3), 3);
            scale = newScale;
            updateTransform();
        }, { passive: false });

        document.getElementById('btn-zoom-in')?.addEventListener('click', () => {
            scale = Math.min(scale * 1.2, 3);
            updateTransform();
        });
        document.getElementById('btn-zoom-out')?.addEventListener('click', () => {
            scale = Math.max(scale * 0.8, 0.3);
            updateTransform();
        });
        document.getElementById('btn-zoom-reset')?.addEventListener('click', () => {
            scale = 1; panX = 40; panY = 40;
            updateTransform();
        });

        // Load Graph Data
        function loadGraph() {
            loadingOverlay.classList.remove('hidden');
            const pillarId = selectPillar.value;
            const onlyIssues = toggleIssues.checked;

            const url = new URL('{{ route("admin.kluster.graph-data") }}', window.location.origin);
            if (pillarId) url.searchParams.append('pillar_id', pillarId);
            if (onlyIssues) url.searchParams.append('only_issues', '1');

            fetch(url)
                .then(r => r.json())
                .then(data => {
                    graphData = data;
                    renderGraph(data);
                    loadingOverlay.classList.add('hidden');
                })
                .catch(() => {
                    loadingOverlay.classList.add('hidden');
                });
        }

        function calculateLayout(nodes, edges) {
            const width = svg.clientWidth || 800;
            const height = svg.clientHeight || 560;

            const pillarNodes = nodes.filter(n => n.is_pillar);
            const otherNodes = nodes.filter(n => !n.is_pillar);

            const positions = {};
            const centerX = width / 2;
            const centerY = height / 2;

            if (pillarNodes.length === 1) {
                const centerPillar = pillarNodes[0];
                positions[centerPillar.id] = { x: centerX, y: centerY };

                const children = otherNodes.filter(n => n.pillar_id === centerPillar.id);
                const orphans = otherNodes.filter(n => n.pillar_id !== centerPillar.id);

                const radius = Math.min(width, height) * 0.36;
                const totalChildren = children.length;

                children.forEach((child, i) => {
                    const angle = (2 * Math.PI * i) / (totalChildren || 1);
                    positions[child.id] = {
                        x: centerX + radius * Math.cos(angle),
                        y: centerY + radius * Math.sin(angle),
                    };
                });

                orphans.forEach((orphan, i) => {
                    positions[orphan.id] = {
                        x: 60 + (i % 4) * 120,
                        y: 50 + Math.floor(i / 4) * 80,
                    };
                });
            } else {
                // Multi-pillar cluster grid
                const cols = Math.max(1, Math.ceil(Math.sqrt(pillarNodes.length || 1)));
                const colWidth = width / cols;
                const rowHeight = height / (Math.ceil((pillarNodes.length || 1) / cols) || 1);

                pillarNodes.forEach((pillar, pIdx) => {
                    const col = pIdx % cols;
                    const row = Math.floor(pIdx / cols);
                    const pX = col * colWidth + colWidth / 2;
                    const pY = row * rowHeight + rowHeight / 2;
                    positions[pillar.id] = { x: pX, y: pY };

                    const children = otherNodes.filter(n => n.pillar_id === pillar.id);
                    const r = 110;
                    children.forEach((child, cIdx) => {
                        const angle = (2 * Math.PI * cIdx) / (children.length || 1);
                        positions[child.id] = {
                            x: pX + r * Math.cos(angle),
                            y: pY + r * Math.sin(angle),
                        };
                    });
                });

                // Standar without pillars
                const standalone = otherNodes.filter(n => !n.pillar_id);
                standalone.forEach((node, i) => {
                    positions[node.id] = {
                        x: 40 + (i % 5) * 100,
                        y: height - 60 - Math.floor(i / 5) * 60,
                    };
                });
            }

            return positions;
        }

        function renderGraph(data) {
            edgesLayer.innerHTML = '';
            nodesLayer.innerHTML = '';

            if (!data.nodes.length) {
                nodesLayer.innerHTML = `<text x="50%" y="50%" text-anchor="middle" fill="#a8a29e" font-size="14">Tidak ada artikel pada filter ini</text>`;
                return;
            }

            const positions = calculateLayout(data.nodes, data.edges);

            // Render Edges
            data.edges.forEach(edge => {
                const src = positions[edge.source];
                const tgt = positions[edge.target];
                if (!src || !tgt) return;

                const isPillarLink = edge.type === 'pillar_link';
                const isLinked = edge.is_linked;

                let strokeColor = '#059669'; // Emerald
                let marker = 'url(#arrow-green)';
                let dash = '';

                if (isPillarLink && !isLinked) {
                    strokeColor = '#d97706'; // Amber broken/missing
                    marker = 'url(#arrow-amber)';
                    dash = 'stroke-dasharray="4 4"';
                } else if (!isPillarLink) {
                    strokeColor = '#1e3a5f';
                    marker = 'url(#arrow-blue)';
                }

                // Curved path
                const dx = tgt.x - src.x;
                const dy = tgt.y - src.y;
                const dr = Math.sqrt(dx * dx + dy * dy) * 1.2;

                const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                path.setAttribute('d', `M ${src.x} ${src.y} A ${dr} ${dr} 0 0,1 ${tgt.x} ${tgt.y}`);
                path.setAttribute('fill', 'none');
                path.setAttribute('stroke', strokeColor);
                path.setAttribute('stroke-width', isPillarLink ? '2' : '1.2');
                path.setAttribute('stroke-opacity', '0.7');
                if (dash) path.setAttribute('stroke-dasharray', '4 4');
                path.setAttribute('marker-end', marker);
                edgesLayer.appendChild(path);
            });

            // Render Nodes
            data.nodes.forEach(node => {
                const pos = positions[node.id];
                if (!pos) return;

                const g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
                g.setAttribute('class', 'graph-node cursor-pointer group');
                g.setAttribute('transform', `translate(${pos.x}, ${pos.y})`);
                g.setAttribute('data-id', node.id);

                let radius = 22;
                let fill = '#a8a29e'; // standard
                let stroke = '#ffffff';

                if (node.is_pillar) {
                    radius = 30;
                    fill = '#1e3a5f';
                } else if (node.pillar_id) {
                    if (node.has_pillar_backlink) {
                        radius = 20;
                        fill = '#059669'; // emerald
                    } else {
                        radius = 22;
                        fill = '#d97706'; // warning amber
                        stroke = '#ef4444';
                    }
                }

                // Node circle
                const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                circle.setAttribute('r', radius);
                circle.setAttribute('fill', fill);
                circle.setAttribute('stroke', stroke);
                circle.setAttribute('stroke-width', '2.5');
                circle.setAttribute('class', 'transition duration-150 hover:scale-110');
                g.appendChild(circle);

                // Warning badge if missing pillar link
                if (node.pillar_id && !node.has_pillar_backlink) {
                    const alertCircle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                    alertCircle.setAttribute('cx', radius * 0.7);
                    alertCircle.setAttribute('cy', -radius * 0.7);
                    alertCircle.setAttribute('r', '7');
                    alertCircle.setAttribute('fill', '#ef4444');
                    alertCircle.setAttribute('stroke', '#ffffff');
                    alertCircle.setAttribute('stroke-width', '1.5');
                    g.appendChild(alertCircle);

                    const exclam = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                    exclam.setAttribute('x', radius * 0.7);
                    exclam.setAttribute('y', -radius * 0.7 + 3);
                    exclam.setAttribute('text-anchor', 'middle');
                    exclam.setAttribute('fill', '#ffffff');
                    exclam.setAttribute('font-size', '9');
                    exclam.setAttribute('font-weight', 'bold');
                    exclam.textContent = '!';
                    g.appendChild(exclam);
                }

                // Node Label inside circle (Short icon / initials)
                const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                text.setAttribute('text-anchor', 'middle');
                text.setAttribute('dy', '4');
                text.setAttribute('fill', '#ffffff');
                text.setAttribute('font-size', node.is_pillar ? '11' : '9');
                text.setAttribute('font-weight', 'bold');
                text.textContent = node.is_pillar ? 'PILAR' : (node.pillar_id ? 'TURUN' : 'STD');
                g.appendChild(text);

                // Title label below node
                const titleText = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                titleText.setAttribute('text-anchor', 'middle');
                titleText.setAttribute('y', radius + 15);
                titleText.setAttribute('fill', '#44403c');
                titleText.setAttribute('font-size', '11');
                titleText.setAttribute('font-weight', node.is_pillar ? 'bold' : 'normal');
                titleText.setAttribute('class', 'pointer-events-none fill-stone-700 dark:fill-stone-300 font-sans');

                const truncatedTitle = node.title.length > 22 ? node.title.slice(0, 20) + '...' : node.title;
                titleText.textContent = truncatedTitle;
                g.appendChild(titleText);

                // Click event -> Select in inspector
                g.addEventListener('click', (e) => {
                    e.stopPropagation();
                    selectNode(node);
                });

                nodesLayer.appendChild(g);
            });
        }

        function selectNode(node) {
            document.getElementById('insp-type-badge').textContent = node.is_pillar ? 'ARTIKEL PILAR (HUB)' : (node.pillar_id ? 'ARTIKEL PENDUKUNG' : 'ARTIKEL STANDAR');
            document.getElementById('insp-type-badge').className = 'inline-flex rounded-md px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider ' +
                (node.is_pillar ? 'bg-[#1e3a5f]/10 text-[#1e3a5f] dark:bg-sky-400/10 dark:text-sky-300' :
                 (node.pillar_id ? (node.has_pillar_backlink ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-950/50 dark:text-amber-300') :
                 'bg-stone-100 text-stone-600 dark:bg-white/[0.06] dark:text-stone-300'));

            document.getElementById('insp-title').textContent = node.title;
            document.getElementById('insp-slug').textContent = '/artikel/' + node.slug;
            document.getElementById('insp-category').textContent = node.category;
            document.getElementById('insp-outgoing-count').textContent = node.outgoing_count;
            document.getElementById('insp-status').textContent = node.status;

            const pillarBox = document.getElementById('insp-pillar-box');
            if (node.pillar_id) {
                pillarBox.classList.remove('hidden');
                document.getElementById('insp-pillar-title').textContent = node.pillar_title || ('Pilar #' + node.pillar_id);
                const statusDiv = document.getElementById('insp-pillar-status');
                if (node.has_pillar_backlink) {
                    statusDiv.innerHTML = '<span class="inline-flex items-center gap-1.5 text-emerald-700 font-semibold text-[11px] dark:text-emerald-400"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg> Tautan Balik Aman</span>';
                } else {
                    statusDiv.innerHTML = '<span class="inline-flex items-center gap-1.5 text-amber-700 font-bold text-[11px] dark:text-amber-400"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Belum Menautkan Balik ke Pilar!</span>';
                }
            } else if (node.is_pillar) {
                pillarBox.classList.remove('hidden');
                document.getElementById('insp-pillar-title').textContent = 'Artikel ini berfungsi sebagai Pilar Utama.';
                document.getElementById('insp-pillar-status').innerHTML = '<span class="text-stone-500 text-[11px]">Memiliki turunan topik di sekitarnya.</span>';
            } else {
                pillarBox.classList.add('hidden');
            }

            document.getElementById('btn-insp-edit').href = node.edit_url;
            document.getElementById('btn-insp-view').href = node.url;
            document.getElementById('insp-actions').classList.remove('hidden');
        }

        // Search highlight
        searchInput?.addEventListener('input', function () {
            const val = this.value.toLowerCase().trim();
            document.querySelectorAll('.graph-node').forEach(g => {
                const id = g.getAttribute('data-id');
                const node = graphData.nodes.find(n => n.id == id);
                if (!node) return;

                if (!val || node.title.toLowerCase().includes(val) || node.slug.includes(val)) {
                    g.style.opacity = '1';
                } else {
                    g.style.opacity = '0.2';
                }
            });
        });

        selectPillar.addEventListener('change', loadGraph);
        toggleIssues.addEventListener('change', loadGraph);

        // Initial fetch
        loadGraph();
    });
</script>
@endpush
