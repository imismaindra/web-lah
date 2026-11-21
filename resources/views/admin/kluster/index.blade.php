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

        {{-- Toast Notification Container --}}
        <div id="quick-toast" class="fixed bottom-6 right-6 z-50 hidden max-w-sm rounded-2xl border border-stone-200 bg-white/95 px-4 py-3 shadow-xl backdrop-blur-md transition-all dark:border-white/10 dark:bg-[#1c1c1a]/95">
            <div class="flex items-center gap-3">
                <span id="toast-icon" class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-emerald-500/15 text-emerald-600 dark:text-emerald-400">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
                </span>
                <p id="toast-msg" class="text-xs font-medium text-stone-800 dark:text-stone-200"></p>
            </div>
        </div>

        {{-- Page Header & Action Bar --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <h2 class="font-serif text-2xl font-bold tracking-tight text-stone-900 dark:text-stone-100">Arsitektur Kluster & Jalur Tautan</h2>
                    <span id="badge-pillar-count" class="inline-flex items-center rounded-full bg-stone-100 px-2.5 py-0.5 text-xs font-semibold text-stone-600 dark:bg-white/[0.06] dark:text-stone-400">
                        {{ $audit['total_pillars'] }} Pilar &bull; {{ $audit['total_supporting'] }} Turunan
                    </span>
                </div>
                <p class="mt-1 text-sm text-stone-500 dark:text-stone-400">Pantau struktur topic cluster, jalur internal link, dan pastikan seluruh artikel turunan menautkan balik ke pilar.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                {{-- View Mode Switcher --}}
                <div class="inline-flex rounded-xl border border-stone-200 bg-stone-100/80 p-1 dark:border-white/10 dark:bg-white/[0.04]">
                    <button
                        type="button"
                        id="btn-tab-visual"
                        class="tab-switch-btn flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition bg-white text-stone-900 shadow-xs dark:bg-[#20201e] dark:text-white"
                    >
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                        <span>Peta Visual</span>
                    </button>
                    <button
                        type="button"
                        id="btn-tab-table"
                        class="tab-switch-btn flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-stone-600 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white"
                    >
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M3 15h18"/><path d="M9 3v18"/></svg>
                        <span>Tabel & Struktur</span>
                    </button>
                </div>

                {{-- Action Buttons --}}
                <form method="POST" action="{{ route('admin.kluster.sync') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl border border-stone-200 bg-white px-3.5 py-2 text-xs font-semibold text-stone-700 shadow-2xs transition hover:bg-stone-50 active:scale-[0.98] dark:border-white/10 dark:bg-[#171716] dark:text-stone-300 dark:hover:bg-white/[0.04]" title="Pindai ulang seluruh tautan dalam artikel">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                        <span>Sinkronkan Jalur</span>
                    </button>
                </form>
                <a href="{{ route('admin.artikel.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-[#1e3a5f] px-3.5 py-2 text-xs font-semibold text-white shadow-2xs transition hover:bg-[#16304a] active:scale-[0.98] dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                    <span>Tulis Artikel</span>
                </a>
            </div>
        </div>

        {{-- Metrics Overview Cards --}}
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4">
            <div class="rounded-2xl border border-stone-200/80 bg-white p-4 transition dark:border-white/[0.06] dark:bg-[#171716]">
                <div class="flex items-center justify-between text-xs text-stone-500 dark:text-stone-400">
                    <span class="font-mono text-[11px] uppercase tracking-wider">Artikel Pilar</span>
                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-[#1e3a5f] dark:bg-[#d4a373]"></span>
                </div>
                <p id="stat-pillars" class="mt-2 font-serif text-3xl font-bold tracking-tight text-stone-900 dark:text-stone-100">{{ $audit['total_pillars'] }}</p>
                <p class="mt-1 text-[11px] text-stone-400 dark:text-stone-500">Halaman induk rujukan topik</p>
            </div>

            <div class="rounded-2xl border border-stone-200/80 bg-white p-4 transition dark:border-white/[0.06] dark:bg-[#171716]">
                <div class="flex items-center justify-between text-xs text-stone-500 dark:text-stone-400">
                    <span class="font-mono text-[11px] uppercase tracking-wider">Artikel Pendukung</span>
                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-stone-400 dark:bg-stone-500"></span>
                </div>
                <p id="stat-supporting" class="mt-2 font-serif text-3xl font-bold tracking-tight text-stone-900 dark:text-stone-100">{{ $audit['total_supporting'] }}</p>
                <p class="mt-1 text-[11px] text-stone-400 dark:text-stone-500">Kajian spesifik turunan pilar</p>
            </div>

            <div class="rounded-2xl border {{ $audit['missing_backlink_count'] > 0 ? 'border-amber-400/80 bg-amber-50/50 dark:border-amber-500/30 dark:bg-amber-950/20' : 'border-stone-200/80 bg-white dark:border-white/[0.06] dark:bg-[#171716]' }} p-4 transition">
                <div class="flex items-center justify-between text-xs {{ $audit['missing_backlink_count'] > 0 ? 'text-amber-700 dark:text-amber-400' : 'text-stone-500 dark:text-stone-400' }}">
                    <span class="font-mono text-[11px] uppercase tracking-wider">Tanpa Tautan Pilar</span>
                    @if ($audit['missing_backlink_count'] > 0)
                        <span class="inline-flex h-2.5 w-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                    @else
                        <span class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                    @endif
                </div>
                <p id="stat-missing" class="mt-2 font-serif text-3xl font-bold tracking-tight {{ $audit['missing_backlink_count'] > 0 ? 'text-amber-900 dark:text-amber-200' : 'text-stone-900 dark:text-stone-100' }}">
                    {{ $audit['missing_backlink_count'] }}
                </p>
                <p class="mt-1 text-[11px] {{ $audit['missing_backlink_count'] > 0 ? 'text-amber-700 font-medium dark:text-amber-400' : 'text-stone-400 dark:text-stone-500' }}">
                    {{ $audit['missing_backlink_count'] > 0 ? 'Wajib ditautkan balik ke pilar' : 'Seluruh turunan terhubung aman' }}
                </p>
            </div>

            <div class="rounded-2xl border border-stone-200/80 bg-white p-4 transition dark:border-white/[0.06] dark:bg-[#171716]">
                <div class="flex items-center justify-between text-xs text-emerald-600 dark:text-emerald-400">
                    <span class="font-mono text-[11px] uppercase tracking-wider">Tautan Sehat</span>
                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                </div>
                <p id="stat-healthy" class="mt-2 font-serif text-3xl font-bold tracking-tight text-stone-900 dark:text-stone-100">{{ $audit['healthy_count'] }}</p>
                <p class="mt-1 text-[11px] text-stone-400 dark:text-stone-500">Turunan dengan tautan balik aktif</p>
            </div>
        </div>

        {{-- Onboarding Banner when 0 Pillars are configured --}}
        @if ($audit['total_pillars'] === 0)
            <div id="onboarding-cluster-banner" class="rounded-2xl border border-stone-200/90 bg-stone-50/80 p-5 shadow-2xs dark:border-white/[0.08] dark:bg-[#171716]">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-3.5">
                        <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-[#1e3a5f]/10 text-[#1e3a5f] dark:bg-[#d4a373]/15 dark:text-[#d4a373]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 6h10"/><path d="M6 10h10"/></svg>
                        </span>
                        <div>
                            <h3 class="font-serif text-base font-bold text-stone-900 dark:text-stone-100">Belum Ada Artikel Pilar yang Ditetapkan</h3>
                            <p class="mt-1 max-w-2xl text-xs text-stone-500 leading-relaxed dark:text-stone-400">
                                Topic Cluster bekerja dengan menjadikan 1 artikel komprehensif sebagai <strong>Artikel Pilar (Hub)</strong>. Artikel-artikel turunan akan mengorbit pilar dan menyalurkan otoritas SEO melalui tautan balik (backlink). Tentukan artikel pilar pertama Anda di bawah ini untuk memulai pemetaan visual.
                            </p>
                        </div>
                    </div>
                    <button
                        type="button"
                        onclick="switchToTableTab()"
                        class="inline-flex flex-shrink-0 items-center gap-2 rounded-xl bg-[#1e3a5f] px-4 py-2.5 text-xs font-semibold text-white shadow-2xs transition hover:bg-[#16304a] dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200"
                    >
                        <span>Tetapkan Pilar di Tabel</span>
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    </button>
                </div>
            </div>
        @endif

        {{-- Alert: Problematic Supporting Articles (Missing Pillar Backlink) --}}
        @if ($audit['missing_backlink_count'] > 0)
            <div id="audit-alert-section" class="overflow-hidden rounded-2xl border border-amber-300/80 bg-amber-50/90 dark:border-amber-500/20 dark:bg-amber-950/25">
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
                                            <span class="h-1.5 w-1.5 rounded-full bg-[#1e3a5f] dark:bg-[#d4a373]"></span>
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

        {{-- TAB CONTENT: 1. PETA VISUAL --}}
        <div id="panel-tab-visual" class="space-y-4">
            <div class="overflow-hidden rounded-2xl border border-stone-200/80 bg-white shadow-2xs dark:border-white/[0.06] dark:bg-[#171716]">
                {{-- Canvas Header Controls Bar --}}
                <div class="flex flex-col gap-3 border-b border-stone-100 p-4 dark:border-white/[0.06] sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-wrap items-center gap-2.5">
                        {{-- Filter Pillar Dropdown --}}
                        <div class="relative">
                            <select id="select-pillar-filter" class="cursor-pointer appearance-none rounded-xl border border-stone-200 bg-stone-50/70 py-2 pl-3.5 pr-8 text-xs font-medium text-stone-700 outline-none transition hover:border-stone-300 focus:border-[#1e3a5f] focus:bg-white dark:border-white/10 dark:bg-[#121211] dark:text-stone-200 dark:hover:border-white/20 dark:focus:border-stone-400 dark:focus:bg-[#121211] dark:[color-scheme:dark]">
                                <option value="" class="bg-white text-stone-800 dark:bg-[#171716] dark:text-stone-200">Semua Kluster Topik</option>
                                @foreach ($pillars as $pil)
                                    <option value="{{ $pil->id }}" class="bg-white text-stone-800 dark:bg-[#171716] dark:text-stone-200">Kluster: {{ $pil->judul }}</option>
                                @endforeach
                            </select>
                            <svg class="pointer-events-none absolute right-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-stone-400 dark:text-stone-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                        </div>

                        {{-- Toggle Issues Only --}}
                        <label class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-stone-200 bg-stone-50/70 px-3 py-2 text-xs font-medium text-stone-700 transition hover:bg-stone-100 dark:border-white/10 dark:bg-[#121211] dark:text-stone-300 dark:hover:bg-white/[0.04]">
                            <input type="checkbox" id="toggle-issues-only" class="rounded border-stone-300 text-[#1e3a5f] focus:ring-0 dark:border-stone-600 dark:bg-[#171716] dark:checked:bg-[#d4a373] dark:focus:ring-0">
                            <span>Hanya Masalah Tautan</span>
                        </label>

                        {{-- Search Node --}}
                        <div class="relative">
                            <input
                                type="text"
                                id="search-node-input"
                                placeholder="Cari artikel di peta..."
                                class="w-44 rounded-xl border border-stone-200 bg-stone-50/70 py-2 pl-8 pr-3 text-xs outline-none transition placeholder:text-stone-400 focus:w-56 focus:border-[#1e3a5f] focus:bg-white dark:border-white/10 dark:bg-[#121211] dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-stone-400 dark:focus:bg-[#121211]"
                            >
                            <svg class="pointer-events-none absolute left-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        </div>
                    </div>

                    {{-- Canvas Zoom & Reset Controls --}}
                    <div class="flex items-center gap-1.5 self-end sm:self-auto">
                        <button type="button" id="btn-zoom-in" class="rounded-lg border border-stone-200 bg-stone-50/70 p-2 text-stone-600 transition hover:bg-stone-100 dark:border-white/10 dark:bg-[#121211] dark:text-stone-300" title="Perbesar">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                        </button>
                        <button type="button" id="btn-zoom-out" class="rounded-lg border border-stone-200 bg-stone-50/70 p-2 text-stone-600 transition hover:bg-stone-100 dark:border-white/10 dark:bg-[#121211] dark:text-stone-300" title="Perkecil">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                        </button>
                        <button type="button" id="btn-zoom-reset" class="rounded-lg border border-stone-200 bg-stone-50/70 px-2.5 py-1.5 text-xs font-semibold text-stone-600 transition hover:bg-stone-100 dark:border-white/10 dark:bg-[#121211] dark:text-stone-300" title="Pusatkan Tampilan">
                            Reset Tampilan
                        </button>
                    </div>
                </div>

                {{-- Legend Strip --}}
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-stone-100 bg-stone-50/50 px-4 py-2.5 text-[11px] text-stone-500 dark:border-white/[0.04] dark:bg-[#141413] dark:text-stone-400">
                    <div class="flex flex-wrap items-center gap-3.5">
                        <span class="font-semibold text-stone-700 dark:text-stone-300">Legenda:</span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="h-3 w-3 rounded-full bg-[#1e3a5f] ring-2 ring-[#1e3a5f]/30 dark:bg-[#d4a373] dark:ring-[#d4a373]/30"></span>
                            <span>Artikel Pilar (Hub)</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="h-3 w-3 rounded-full bg-emerald-600 ring-2 ring-emerald-500/30"></span>
                            <span>Turunan Aman</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="h-3 w-3 rounded-full bg-amber-500 ring-2 ring-amber-500/30"></span>
                            <span>Turunan Belum Tautkan Pilar</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="h-3 w-3 rounded-full bg-stone-400 dark:bg-stone-500"></span>
                            <span>Artikel Standar</span>
                        </span>
                    </div>
                    <span class="hidden text-stone-400 md:inline">Klik node untuk detail inspeksi & ubah peran. Geser (drag) untuk merapikan canvas.</span>
                </div>

                {{-- Interactive Canvas Area & Floating Inspector --}}
                <div class="relative min-h-[580px] w-full bg-stone-100/40 dark:bg-[#111110]">
                    <svg id="cluster-svg" class="h-[620px] w-full cursor-grab active:cursor-grabbing select-none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <style>
                                .canvas-dot { fill: #d6d3d1; }
                                .dark .canvas-dot { fill: #242422; }
                                .graph-edge { transition: stroke-opacity 0.15s ease, stroke-width 0.15s ease; }
                            </style>
                            {{-- Dot Grid Background --}}
                            <pattern id="canvas-grid" width="28" height="28" patternUnits="userSpaceOnUse">
                                <circle cx="2" cy="2" r="1.1" class="canvas-dot" />
                            </pattern>
                            <marker id="arrow-green" viewBox="0 0 10 10" refX="22" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                                <path d="M 0 0 L 10 5 L 0 10 z" fill="#10b981" />
                            </marker>
                            <marker id="arrow-amber" viewBox="0 0 10 10" refX="22" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                                <path d="M 0 0 L 10 5 L 0 10 z" fill="#f59e0b" />
                            </marker>
                            <marker id="arrow-normal" viewBox="0 0 10 10" refX="22" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                                <path d="M 0 0 L 10 5 L 0 10 z" fill="#78716c" />
                            </marker>
                        </defs>

                        {{-- Background Grid Rect --}}
                        <rect width="100%" height="100%" fill="url(#canvas-grid)" />

                        {{-- Viewport for Pan & Zoom --}}
                        <g id="svg-viewport">
                            <g id="svg-edges"></g>
                            <g id="svg-nodes"></g>
                        </g>
                    </svg>

                    {{-- Loading Overlay --}}
                    <div id="graph-loading" class="absolute inset-0 flex items-center justify-center bg-white/70 backdrop-blur-2xs dark:bg-black/60 hidden">
                        <div class="flex items-center gap-2.5 rounded-2xl bg-white px-4 py-2.5 text-xs font-semibold text-stone-800 shadow-md dark:bg-stone-800 dark:text-stone-200">
                            <svg class="h-4 w-4 animate-spin text-[#1e3a5f] dark:text-[#d4a373]" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10" stroke-width="4" class="opacity-25"/><path d="M4 12a8 8 0 0 1 8-8" stroke-width="4" class="opacity-75"/></svg>
                            <span>Memuat struktur kluster...</span>
                        </div>
                    </div>

                    {{-- Floating Inspector Panel (Slide-over drawer on right) --}}
                    <div
                        id="inspector-drawer"
                        class="absolute right-4 top-4 z-20 hidden w-80 max-w-[calc(100vw-2rem)] rounded-2xl border border-stone-200/90 bg-white/95 p-4 shadow-xl backdrop-blur-md transition-all dark:border-white/10 dark:bg-[#171716]/98 sm:w-96"
                    >
                        <div class="flex items-start justify-between border-b border-stone-100 pb-3 dark:border-white/[0.06]">
                            <div class="space-y-1">
                                <span id="insp-type-badge" class="inline-flex rounded-md bg-stone-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-stone-600 dark:bg-white/[0.06] dark:text-stone-300">
                                    Artikel Standar
                                </span>
                                <h4 id="insp-title" class="font-serif text-sm font-bold leading-snug text-stone-900 line-clamp-2 dark:text-stone-100">
                                    Judul Artikel
                                </h4>
                                <p id="insp-slug" class="text-[11px] text-stone-400 font-mono line-clamp-1">/artikel/slug</p>
                            </div>
                            <button
                                type="button"
                                onclick="closeInspector()"
                                class="rounded-lg p-1 text-stone-400 transition hover:bg-stone-100 hover:text-stone-700 dark:hover:bg-white/[0.06] dark:hover:text-stone-200"
                                title="Tutup Panel"
                            >
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </button>
                        </div>

                        <div class="mt-3 space-y-3.5 text-xs">
                            {{-- Role Quick Manager --}}
                            <div class="rounded-xl border border-stone-200/80 bg-stone-50/70 p-3 dark:border-white/10 dark:bg-[#121211]">
                                <label class="block text-[11px] font-semibold text-stone-700 dark:text-stone-300 mb-1.5">
                                    Atur Peran Kluster Artikel:
                                </label>
                                <div class="space-y-2">
                                    <select id="insp-role-select" class="w-full rounded-lg border border-stone-200 bg-white px-2.5 py-1.5 text-xs font-medium text-stone-800 outline-none transition focus:border-[#1e3a5f] dark:border-white/10 dark:bg-[#181817] dark:text-stone-200 dark:focus:border-stone-400 dark:focus:bg-[#181817] dark:[color-scheme:dark]">
                                        <option value="standar" class="bg-white text-stone-800 dark:bg-[#171716] dark:text-stone-200">Artikel Standar (Mandiri)</option>
                                        <option value="pilar" class="bg-white text-stone-800 dark:bg-[#171716] dark:text-stone-200">Jadikan Artikel Pilar (Hub)</option>
                                        <option value="pendukung" class="bg-white text-stone-800 dark:bg-[#171716] dark:text-stone-200">Jadikan Artikel Turunan</option>
                                    </select>

                                    <div id="insp-parent-pillar-wrapper" class="hidden space-y-1">
                                        <label class="block text-[10px] font-medium text-stone-500">Pilih Pilar Induk:</label>
                                        <select id="insp-parent-pillar-select" class="w-full rounded-lg border border-stone-200 bg-white px-2.5 py-1.5 text-xs text-stone-800 outline-none focus:border-[#1e3a5f] dark:border-white/10 dark:bg-[#181817] dark:text-stone-200 dark:focus:border-stone-400 dark:focus:bg-[#181817] dark:[color-scheme:dark]">
                                            @foreach ($pillars as $pil)
                                                <option value="{{ $pil->id }}" class="bg-white text-stone-800 dark:bg-[#171716] dark:text-stone-200">{{ $pil->judul }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <button
                                        type="button"
                                        id="btn-save-role-insp"
                                        onclick="saveInspectorRole()"
                                        class="flex w-full items-center justify-center gap-1.5 rounded-lg bg-[#1e3a5f] px-3 py-1.5 text-xs font-semibold text-white shadow-2xs transition hover:bg-[#16304a] active:scale-[0.98] dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200"
                                    >
                                        <span>Simpan Peran Baru</span>
                                    </button>
                                </div>
                            </div>

                            {{-- Pillar Backlink Status Box (if Supporting) --}}
                            <div id="insp-pillar-box" class="hidden rounded-xl border p-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-semibold text-stone-700 dark:text-stone-300">Pilar Induk Terkait:</span>
                                    <span id="insp-backlink-indicator" class="inline-flex items-center gap-1 text-[10px] font-bold"></span>
                                </div>
                                <p id="insp-pillar-title" class="font-serif font-bold text-stone-900 dark:text-stone-100 mt-1 line-clamp-1">-</p>
                                <div id="insp-backlink-alert" class="mt-2 text-[11px] leading-relaxed"></div>
                            </div>

                            {{-- Stats Grid --}}
                            <div class="grid grid-cols-2 gap-2 text-center">
                                <div class="rounded-xl border border-stone-100 bg-stone-50/50 p-2.5 dark:border-white/[0.04] dark:bg-white/[0.02]">
                                    <span class="text-[10px] text-stone-400 uppercase font-semibold">Tautan Keluar</span>
                                    <p id="insp-outgoing-count" class="font-serif text-lg font-bold text-stone-800 dark:text-stone-200 mt-0.5">0</p>
                                </div>
                                <div class="rounded-xl border border-stone-100 bg-stone-50/50 p-2.5 dark:border-white/[0.04] dark:bg-white/[0.02]">
                                    <span class="text-[10px] text-stone-400 uppercase font-semibold">Tautan Masuk</span>
                                    <p id="insp-incoming-count" class="font-serif text-lg font-bold text-stone-800 dark:text-stone-200 mt-0.5">0</p>
                                </div>
                            </div>

                            {{-- Metadata --}}
                            <div class="flex items-center justify-between text-[11px] text-stone-500 border-t border-stone-100 pt-2 dark:border-white/[0.06]">
                                <span>Kategori: <strong id="insp-category" class="font-medium text-stone-700 dark:text-stone-300">-</strong></span>
                                <span>Status: <strong id="insp-status" class="font-medium capitalize text-stone-700 dark:text-stone-300">-</strong></span>
                            </div>

                            {{-- Action Links --}}
                            <div class="flex items-center gap-2 pt-1">
                                <a id="btn-insp-edit" href="#" class="flex flex-1 items-center justify-center gap-1.5 rounded-xl border border-stone-200 bg-white px-3 py-2 text-xs font-semibold text-stone-800 shadow-2xs transition hover:bg-stone-50 dark:border-white/10 dark:bg-white/[0.04] dark:text-stone-200 dark:hover:bg-white/[0.08]">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                    <span>Edit Naskah</span>
                                </a>
                                <a id="btn-insp-view" href="#" target="_blank" rel="noopener" class="flex items-center justify-center rounded-xl border border-stone-200 bg-white p-2 text-stone-600 transition hover:bg-stone-50 dark:border-white/10 dark:bg-white/[0.04] dark:text-stone-300" title="Buka Halaman Web">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB CONTENT: 2. TABEL & STRUKTUR KLUSTER --}}
        <div id="panel-tab-table" class="space-y-4 hidden">
            <div class="overflow-hidden rounded-2xl border border-stone-200/80 bg-white shadow-2xs dark:border-white/[0.06] dark:bg-[#171716]">
                {{-- Table Filter Bar --}}
                <div class="flex flex-col gap-3 border-b border-stone-100 p-4 dark:border-white/[0.06] sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <div class="relative">
                            <input
                                type="text"
                                id="table-search-input"
                                placeholder="Cari judul artikel..."
                                class="w-56 rounded-xl border border-stone-200 bg-stone-50/70 py-2 pl-8 pr-3 text-xs outline-none transition placeholder:text-stone-400 focus:w-72 focus:border-[#1e3a5f] focus:bg-white dark:border-white/10 dark:bg-[#121211] dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-stone-400 dark:focus:bg-[#121211]"
                            >
                            <svg class="pointer-events-none absolute left-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        </div>

                        <select id="table-role-filter" class="cursor-pointer appearance-none rounded-xl border border-stone-200 bg-stone-50/70 py-2 pl-3 pr-8 text-xs font-medium text-stone-700 outline-none transition hover:border-stone-300 focus:border-[#1e3a5f] focus:bg-white dark:border-white/10 dark:bg-[#121211] dark:text-stone-300 dark:hover:border-white/20 dark:focus:border-stone-400 dark:focus:bg-[#121211] dark:[color-scheme:dark]">
                            <option value="" class="bg-white text-stone-800 dark:bg-[#171716] dark:text-stone-200">Semua Peran</option>
                            <option value="pilar" class="bg-white text-stone-800 dark:bg-[#171716] dark:text-stone-200">Hanya Pilar (Hub)</option>
                            <option value="pendukung" class="bg-white text-stone-800 dark:bg-[#171716] dark:text-stone-200">Hanya Turunan</option>
                            <option value="standar" class="bg-white text-stone-800 dark:bg-[#171716] dark:text-stone-200">Hanya Standar</option>
                            <option value="missing" class="bg-white text-stone-800 dark:bg-[#171716] dark:text-stone-200">Turunan Tanpa Tautan Pilar</option>
                        </select>
                    </div>

                    <div class="text-xs text-stone-400">
                        Total: <strong class="text-stone-700 dark:text-stone-300">{{ $artikels->count() }}</strong> artikel
                    </div>
                </div>

                {{-- Articles Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs" id="cluster-articles-table">
                        <thead class="border-b border-stone-100 bg-stone-50/60 font-semibold text-stone-600 dark:border-white/[0.06] dark:bg-white/[0.02] dark:text-stone-400">
                            <tr>
                                <th class="py-3 pl-5 pr-3">Artikel</th>
                                <th class="px-3 py-3">Peran Kluster</th>
                                <th class="px-3 py-3">Pilar Induk</th>
                                <th class="px-3 py-3 text-center">Tautan Keluar</th>
                                <th class="px-3 py-3 text-center">Tautan Masuk</th>
                                <th class="px-3 py-3">Status Tautan Balik</th>
                                <th class="py-3 pl-3 pr-5 text-right">Ubah Peran / Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 dark:divide-white/[0.04]">
                            @forelse ($artikels as $art)
                                @php
                                    $isPilar = (bool) $art->is_pillar;
                                    $isSupporting = (bool) $art->pillar_id;
                                    $hasBacklink = $isSupporting ? app(\App\Services\ContentClusterService::class)->hasPillarBacklink($art) : true;
                                    $roleCategory = $isPilar ? 'pilar' : ($isSupporting ? 'pendukung' : 'standar');
                                @endphp
                                <tr
                                    class="article-row transition hover:bg-stone-50/50 dark:hover:bg-white/[0.02]"
                                    data-title="{{ strtolower($art->judul) }}"
                                    data-role="{{ $roleCategory }}"
                                    data-missing="{{ ($isSupporting && !$hasBacklink) ? '1' : '0' }}"
                                    id="row-artikel-{{ $art->id }}"
                                >
                                    <td class="py-3.5 pl-5 pr-3 font-medium text-stone-900 dark:text-stone-100">
                                        <div class="line-clamp-1 max-w-sm font-serif text-sm font-semibold">
                                            {{ $art->judul }}
                                        </div>
                                        <div class="mt-0.5 flex items-center gap-2 text-[11px] text-stone-400">
                                            <span>/artikel/{{ $art->slug }}</span>
                                            <span>&bull;</span>
                                            <span>{{ $art->kategori->nama ?? 'Tanpa Kategori' }}</span>
                                        </div>
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-3.5">
                                        @if ($isPilar)
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#1e3a5f]/10 px-2.5 py-1 text-[11px] font-bold text-[#1e3a5f] dark:bg-amber-500/15 dark:text-amber-400">
                                                <span class="h-1.5 w-1.5 rounded-full bg-[#1e3a5f] dark:bg-[#d4a373]"></span>
                                                Pilar (Hub)
                                            </span>
                                        @elseif ($isSupporting)
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-stone-100 px-2.5 py-1 text-[11px] font-semibold text-stone-700 dark:bg-white/[0.06] dark:text-stone-300">
                                                <span class="h-1.5 w-1.5 rounded-full bg-stone-500"></span>
                                                Turunan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-stone-100 px-2.5 py-0.5 text-[11px] font-medium text-stone-500 dark:bg-white/[0.04] dark:text-stone-400">
                                                Standar
                                            </span>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-3.5 text-stone-700 dark:text-stone-300">
                                        @if ($art->pillar)
                                            <span class="inline-flex items-center gap-1 font-medium">
                                                {{ $art->pillar->judul }}
                                            </span>
                                        @else
                                            <span class="text-stone-400">-</span>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-3.5 text-center">
                                        <span class="rounded-md bg-stone-100 px-2 py-0.5 text-[11px] font-semibold text-stone-700 dark:bg-white/[0.06] dark:text-stone-300">
                                            {{ $art->outgoingLinks->count() }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-3.5 text-center">
                                        <span class="rounded-md bg-stone-100 px-2 py-0.5 text-[11px] font-semibold text-stone-700 dark:bg-white/[0.06] dark:text-stone-300">
                                            {{ $art->incomingLinks->count() }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-3.5">
                                        @if ($isSupporting)
                                            @if ($hasBacklink)
                                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600 dark:text-emerald-400">
                                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
                                                    <span>Menautkan Pilar</span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 rounded-md bg-amber-500/10 px-2 py-0.5 text-[11px] font-bold text-amber-700 dark:text-amber-400">
                                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                                    <span>Belum Ada Tautan</span>
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-stone-400">-</span>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap py-3.5 pl-3 pr-5 text-right">
                                        <div class="inline-flex items-center gap-1.5">
                                            <button
                                                type="button"
                                                onclick="openQuickAssignModal({{ $art->id }}, '{{ addslashes($art->judul) }}', '{{ $roleCategory }}', '{{ $art->pillar_id }}')"
                                                class="rounded-lg border border-stone-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-stone-700 shadow-2xs transition hover:bg-stone-50 dark:border-white/10 dark:bg-white/[0.04] dark:text-stone-300 dark:hover:bg-white/[0.08]"
                                            >
                                                Ubah Peran
                                            </button>
                                            <a
                                                href="{{ route('admin.artikel.edit', $art) }}"
                                                class="rounded-lg bg-[#1e3a5f] p-1.5 text-white transition hover:bg-[#16304a] dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200"
                                                title="Edit Artikel"
                                            >
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-xs text-stone-400">
                                        Belum ada artikel yang tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal Quick Assign Role --}}
        <div id="modal-quick-assign" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-2xs p-4 hidden">
            <div class="w-full max-w-md rounded-2xl border border-stone-200/90 bg-white p-5 shadow-2xl dark:border-white/10 dark:bg-[#171716]">
                <div class="flex items-start justify-between border-b border-stone-100 pb-3 dark:border-white/[0.06]">
                    <div>
                        <h3 class="font-serif text-base font-bold text-stone-900 dark:text-stone-100">Ubah Peran Kluster Artikel</h3>
                        <p id="modal-assign-title" class="text-xs text-stone-500 dark:text-stone-400 mt-0.5 line-clamp-1"></p>
                    </div>
                    <button type="button" onclick="closeQuickAssignModal()" class="rounded-lg p-1 text-stone-400 hover:bg-stone-100 dark:hover:bg-white/[0.06]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>

                <form id="form-quick-assign" class="mt-4 space-y-4" onsubmit="submitQuickAssign(event)">
                    <input type="hidden" id="modal-article-id">

                    <div>
                        <label class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1">
                            Pilih Peran:
                        </label>
                        <select id="modal-role-select" class="w-full rounded-xl border border-stone-200 bg-stone-50/70 p-2.5 text-xs text-stone-800 outline-none transition hover:border-stone-300 focus:border-[#1e3a5f] focus:bg-white dark:border-white/10 dark:bg-[#121211] dark:text-stone-200 dark:hover:border-white/20 dark:focus:border-stone-400 dark:focus:bg-[#121211] dark:[color-scheme:dark]">
                            <option value="standar" class="bg-white text-stone-800 dark:bg-[#171716] dark:text-stone-200">Artikel Standar (Mandiri)</option>
                            <option value="pilar" class="bg-white text-stone-800 dark:bg-[#171716] dark:text-stone-200">Artikel Pilar (Hub)</option>
                            <option value="pendukung" class="bg-white text-stone-800 dark:bg-[#171716] dark:text-stone-200">Artikel Turunan</option>
                        </select>
                    </div>

                    <div id="modal-pillar-select-container" class="hidden">
                        <label class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1">
                            Pilar Induk Terkait:
                        </label>
                        <select id="modal-pillar-id-select" class="w-full rounded-xl border border-stone-200 bg-stone-50/70 p-2.5 text-xs text-stone-800 outline-none transition hover:border-stone-300 focus:border-[#1e3a5f] focus:bg-white dark:border-white/10 dark:bg-[#121211] dark:text-stone-200 dark:hover:border-white/20 dark:focus:border-stone-400 dark:focus:bg-[#121211] dark:[color-scheme:dark]">
                            @foreach ($pillars as $pil)
                                <option value="{{ $pil->id }}" class="bg-white text-stone-800 dark:bg-[#171716] dark:text-stone-200">{{ $pil->judul }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-stone-100 dark:border-white/[0.06]">
                        <button type="button" onclick="closeQuickAssignModal()" class="rounded-xl border border-stone-200 px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-50 dark:border-white/10 dark:text-stone-300 dark:hover:bg-white/[0.04]">
                            Batal
                        </button>
                        <button type="submit" id="btn-submit-quick-assign" class="inline-flex items-center gap-1.5 rounded-xl bg-[#1e3a5f] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#16304a] dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200">
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
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
        const inspectorDrawer = document.getElementById('inspector-drawer');

        let graphData = { nodes: [], edges: [] };
        let nodePositions = {};
        let scale = 1;
        let panX = 40;
        let panY = 40;
        let isPanning = false;
        let panStartX, panStartY;
        let activeSelectedNode = null;

        // Node Dragging State
        let draggedNodeId = null;
        let dragOffset = { x: 0, y: 0 };

        function getGraphTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            return {
                isDark,
                pillarFill: isDark ? '#d4a373' : '#1e3a5f',
                pillarText: isDark ? '#1c1917' : '#ffffff',
                pillarRing: isDark ? 'rgba(212, 163, 115, 0.22)' : 'rgba(30, 58, 95, 0.12)',
                pillarStroke: isDark ? '#fef3c7' : '#ffffff',

                healthyFill: isDark ? '#059669' : '#059669',
                healthyText: '#ffffff',
                healthyRing: isDark ? 'rgba(16, 185, 129, 0.22)' : 'rgba(5, 150, 105, 0.12)',
                healthyStroke: '#ffffff',

                brokenFill: isDark ? '#d97706' : '#d97706',
                brokenText: '#ffffff',
                brokenRing: isDark ? 'rgba(245, 158, 11, 0.28)' : 'rgba(217, 119, 6, 0.16)',
                brokenStroke: '#ffffff',

                standardFill: isDark ? '#262624' : '#ffffff',
                standardText: isDark ? '#d6d3d1' : '#57534e',
                standardRing: isDark ? 'rgba(255, 255, 255, 0.03)' : 'rgba(0, 0, 0, 0.03)',
                standardStroke: isDark ? '#44403c' : '#d6d3d1',

                labelBg: isDark ? '#1c1c1a' : '#ffffff',
                labelBorder: isDark ? 'rgba(255, 255, 255, 0.1)' : '#e7e5e4',
                labelText: isDark ? '#e7e5e4' : '#1c1917',

                edgeNormal: isDark ? '#78716c' : '#64748b',
                edgeHealthy: isDark ? '#10b981' : '#059669',
                edgeBroken: isDark ? '#f59e0b' : '#d97706',
            };
        }

        function updateTransform() {
            viewport.setAttribute('transform', `translate(${panX}, ${panY}) scale(${scale})`);
        }

        // Tab Switching
        const btnTabVisual = document.getElementById('btn-tab-visual');
        const btnTabTable = document.getElementById('btn-tab-table');
        const panelTabVisual = document.getElementById('panel-tab-visual');
        const panelTabTable = document.getElementById('panel-tab-table');

        function setTab(tab) {
            if (tab === 'visual') {
                panelTabVisual.classList.remove('hidden');
                panelTabTable.classList.add('hidden');
                btnTabVisual.className = 'tab-switch-btn flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition bg-white text-stone-900 shadow-xs dark:bg-[#20201e] dark:text-white';
                btnTabTable.className = 'tab-switch-btn flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-stone-600 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white';
                if (!Object.keys(nodePositions).length && graphData.nodes.length) {
                    renderGraph(graphData);
                }
            } else {
                panelTabVisual.classList.add('hidden');
                panelTabTable.classList.remove('hidden');
                btnTabTable.className = 'tab-switch-btn flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition bg-white text-stone-900 shadow-xs dark:bg-[#20201e] dark:text-white';
                btnTabVisual.className = 'tab-switch-btn flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-stone-600 transition hover:text-stone-900 dark:text-stone-400 dark:hover:text-white';
            }
        }

        btnTabVisual?.addEventListener('click', () => setTab('visual'));
        btnTabTable?.addEventListener('click', () => setTab('table'));

        window.switchToTableTab = function () {
            setTab('table');
            document.getElementById('table-search-input')?.focus();
        };

        // Zoom & Pan listeners
        svg.addEventListener('mousedown', (e) => {
            if (e.target.closest('.graph-node-group')) return;
            isPanning = true;
            panStartX = e.clientX - panX;
            panStartY = e.clientY - panY;
        });

        window.addEventListener('mousemove', (e) => {
            if (isPanning) {
                panX = e.clientX - panStartX;
                panY = e.clientY - panStartY;
                updateTransform();
            } else if (draggedNodeId) {
                const svgRect = svg.getBoundingClientRect();
                const mouseSvgX = (e.clientX - svgRect.left - panX) / scale;
                const mouseSvgY = (e.clientY - svgRect.top - panY) / scale;

                nodePositions[draggedNodeId] = {
                    x: mouseSvgX - dragOffset.x,
                    y: mouseSvgY - dragOffset.y,
                };

                updateNodeAndEdges(draggedNodeId);
            }
        });

        window.addEventListener('mouseup', () => {
            isPanning = false;
            draggedNodeId = null;
        });

        svg.addEventListener('wheel', (e) => {
            e.preventDefault();
            const zoomFactor = e.deltaY < 0 ? 1.08 : 0.92;
            const newScale = Math.min(Math.max(scale * zoomFactor, 0.25), 3);
            scale = newScale;
            updateTransform();
        }, { passive: false });

        document.getElementById('btn-zoom-in')?.addEventListener('click', () => {
            scale = Math.min(scale * 1.2, 3);
            updateTransform();
        });
        document.getElementById('btn-zoom-out')?.addEventListener('click', () => {
            scale = Math.max(scale * 0.8, 0.25);
            updateTransform();
        });
        document.getElementById('btn-zoom-reset')?.addEventListener('click', () => {
            scale = 1;
            panX = 40;
            panY = 40;
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

        function calculateSpaciousLayout(nodes, edges) {
            const width = Math.max(svg.clientWidth || 900, 800);
            const height = Math.max(svg.clientHeight || 620, 600);

            const pillarNodes = nodes.filter(n => n.is_pillar);
            const otherNodes = nodes.filter(n => !n.is_pillar);

            const positions = {};
            const centerX = width / 2;
            const centerY = height / 2;

            if (pillarNodes.length === 0) {
                const total = nodes.length;
                const cols = Math.max(2, Math.min(4, Math.ceil(Math.sqrt(total))));
                const rows = Math.ceil(total / cols);

                const colSpacing = 240;
                const rowSpacing = 140;

                const startX = Math.max(100, centerX - ((cols - 1) * colSpacing) / 2);
                const startY = Math.max(90, centerY - ((rows - 1) * rowSpacing) / 2);

                nodes.forEach((node, i) => {
                    const c = i % cols;
                    const r = Math.floor(i / cols);
                    positions[node.id] = {
                        x: startX + c * colSpacing,
                        y: startY + r * rowSpacing,
                    };
                });
            } else if (pillarNodes.length === 1) {
                const centerPillar = pillarNodes[0];
                positions[centerPillar.id] = { x: centerX - 60, y: centerY };

                const children = otherNodes.filter(n => n.pillar_id === centerPillar.id);
                const orphans = otherNodes.filter(n => n.pillar_id !== centerPillar.id);

                const radius = Math.min(width, height) * 0.38;
                const totalChildren = children.length;

                children.forEach((child, i) => {
                    const angle = (2 * Math.PI * i) / (totalChildren || 1);
                    positions[child.id] = {
                        x: (centerX - 60) + radius * Math.cos(angle),
                        y: centerY + radius * Math.sin(angle),
                    };
                });

                orphans.forEach((orphan, i) => {
                    positions[orphan.id] = {
                        x: width - 200,
                        y: 80 + i * 110,
                    };
                });
            } else {
                const cols = Math.max(1, Math.min(3, Math.ceil(Math.sqrt(pillarNodes.length))));
                const colWidth = width / cols;
                const rowHeight = height / Math.ceil(pillarNodes.length / cols);

                pillarNodes.forEach((pillar, pIdx) => {
                    const col = pIdx % cols;
                    const row = Math.floor(pIdx / cols);
                    const pX = col * colWidth + colWidth / 2;
                    const pY = row * rowHeight + rowHeight / 2;
                    positions[pillar.id] = { x: pX, y: pY };

                    const children = otherNodes.filter(n => n.pillar_id === pillar.id);
                    const r = 140;
                    children.forEach((child, cIdx) => {
                        const angle = (2 * Math.PI * cIdx) / (children.length || 1);
                        positions[child.id] = {
                            x: pX + r * Math.cos(angle),
                            y: pY + r * Math.sin(angle),
                        };
                    });
                });

                const standalone = otherNodes.filter(n => !n.pillar_id);
                standalone.forEach((node, i) => {
                    positions[node.id] = {
                        x: 80 + (i % 4) * 220,
                        y: height + 60 + Math.floor(i / 4) * 120,
                    };
                });
            }

            // Spring relaxation collision push
            const nodeIds = Object.keys(positions);
            for (let iter = 0; iter < 45; iter++) {
                for (let i = 0; i < nodeIds.length; i++) {
                    for (let j = i + 1; j < nodeIds.length; j++) {
                        const n1 = positions[nodeIds[i]];
                        const n2 = positions[nodeIds[j]];
                        const dx = n2.x - n1.x;
                        const dy = n2.y - n1.y;
                        const dist = Math.sqrt(dx * dx + dy * dy) || 1;
                        const minDist = 160;
                        if (dist < minDist) {
                            const force = (minDist - dist) / 2;
                            const fx = (dx / dist) * force;
                            const fy = (dy / dist) * force;
                            n2.x += fx;
                            n2.y += fy;
                            n1.x -= fx;
                            n1.y -= fy;
                        }
                    }
                }
            }

            return positions;
        }

        function renderGraph(data) {
            edgesLayer.innerHTML = '';
            nodesLayer.innerHTML = '';

            const theme = getGraphTheme();

            if (!data.nodes.length) {
                nodesLayer.innerHTML = `
                    <g transform="translate(400, 280)">
                        <text text-anchor="middle" fill="#a8a29e" font-size="14" font-family="Plus Jakarta Sans, sans-serif">
                            Tidak ada artikel yang sesuai dengan filter ini.
                        </text>
                    </g>
                `;
                return;
            }

            // Keep positions or calculate fresh
            if (!Object.keys(nodePositions).length) {
                nodePositions = calculateSpaciousLayout(data.nodes, data.edges);
            }

            // Render Edges
            data.edges.forEach((edge) => {
                const src = nodePositions[edge.source];
                const tgt = nodePositions[edge.target];
                if (!src || !tgt) return;

                const isPillarLink = edge.type === 'pillar_link';
                const isLinked = edge.is_linked;

                let strokeColor = theme.edgeHealthy;
                let marker = 'url(#arrow-green)';
                let isDashed = false;

                if (isPillarLink && !isLinked) {
                    strokeColor = theme.edgeBroken;
                    marker = 'url(#arrow-amber)';
                    isDashed = true;
                } else if (!isPillarLink) {
                    strokeColor = theme.edgeNormal;
                    marker = 'url(#arrow-normal)';
                }

                const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                path.setAttribute('id', `edge-${edge.source}-${edge.target}`);
                path.setAttribute('class', `graph-edge edge-src-${edge.source} edge-tgt-${edge.target}`);
                path.setAttribute('fill', 'none');
                path.setAttribute('stroke', strokeColor);
                path.setAttribute('stroke-width', isPillarLink ? '2.4' : '1.5');
                path.setAttribute('stroke-opacity', '0.75');
                if (isDashed) path.setAttribute('stroke-dasharray', '5 4');
                path.setAttribute('marker-end', marker);

                setEdgeCurve(path, src, tgt);
                edgesLayer.appendChild(path);
            });

            // Render Nodes
            data.nodes.forEach(node => {
                const pos = nodePositions[node.id];
                if (!pos) return;

                const isPillar = node.is_pillar;
                const isSupporting = !isPillar && !!node.pillar_id;
                const isHealthy = isSupporting && node.has_pillar_backlink;
                const isBroken = isSupporting && !node.has_pillar_backlink;

                let ringColor = theme.standardRing;
                let fillColor = theme.standardFill;
                let strokeColor = theme.standardStroke;
                let textColor = theme.standardText;
                let nodeRadius = isPillar ? 28 : (isSupporting ? 22 : 18);
                let badgeText = isPillar ? 'PILAR' : (isSupporting ? 'TUR' : 'STD');

                if (isPillar) {
                    ringColor = theme.pillarRing;
                    fillColor = theme.pillarFill;
                    strokeColor = theme.pillarStroke;
                    textColor = theme.pillarText;
                } else if (isHealthy) {
                    ringColor = theme.healthyRing;
                    fillColor = theme.healthyFill;
                    strokeColor = theme.healthyStroke;
                    textColor = theme.healthyText;
                } else if (isBroken) {
                    ringColor = theme.brokenRing;
                    fillColor = theme.brokenFill;
                    strokeColor = theme.brokenStroke;
                    textColor = theme.brokenText;
                }

                const group = document.createElementNS('http://www.w3.org/2000/svg', 'g');
                group.setAttribute('id', `node-group-${node.id}`);
                group.setAttribute('class', 'graph-node-group cursor-pointer');
                group.setAttribute('transform', `translate(${pos.x}, ${pos.y})`);

                // Outer Halo Circle
                const outerCircle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                outerCircle.setAttribute('r', (nodeRadius + 6).toString());
                outerCircle.setAttribute('fill', ringColor);
                group.appendChild(outerCircle);

                // Main Circle
                const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                circle.setAttribute('r', nodeRadius.toString());
                circle.setAttribute('fill', fillColor);
                circle.setAttribute('stroke', strokeColor);
                circle.setAttribute('stroke-width', '2.5');
                group.appendChild(circle);

                // Short Badge Text inside node
                const badgeSvg = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                badgeSvg.setAttribute('text-anchor', 'middle');
                badgeSvg.setAttribute('dy', '4');
                badgeSvg.setAttribute('fill', textColor);
                badgeSvg.setAttribute('font-size', isPillar ? '10' : '9');
                badgeSvg.setAttribute('font-weight', '700');
                badgeSvg.setAttribute('font-family', 'Plus Jakarta Sans, sans-serif');
                badgeSvg.textContent = badgeText;
                group.appendChild(badgeSvg);

                // Label Background Pill
                const titleStr = node.title.length > 24 ? node.title.substring(0, 22) + '...' : node.title;
                const labelWidth = Math.min(220, Math.max(90, titleStr.length * 7 + 16));

                const labelRect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
                labelRect.setAttribute('x', (-labelWidth / 2).toString());
                labelRect.setAttribute('y', (nodeRadius + 8).toString());
                labelRect.setAttribute('width', labelWidth.toString());
                labelRect.setAttribute('height', '20');
                labelRect.setAttribute('rx', '6');
                labelRect.setAttribute('fill', theme.labelBg);
                labelRect.setAttribute('stroke', theme.labelBorder);
                labelRect.setAttribute('stroke-width', '1');
                group.appendChild(labelRect);

                // Label Text
                const labelText = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                labelText.setAttribute('text-anchor', 'middle');
                labelText.setAttribute('x', '0');
                labelText.setAttribute('y', (nodeRadius + 22).toString());
                labelText.setAttribute('fill', theme.labelText);
                labelText.setAttribute('font-size', '10.5');
                labelText.setAttribute('font-weight', '600');
                labelText.setAttribute('font-family', 'Plus Jakarta Sans, serif');
                labelText.textContent = titleStr;
                group.appendChild(labelText);

                // Tooltip
                const titleElem = document.createElementNS('http://www.w3.org/2000/svg', 'title');
                titleElem.textContent = `${node.title}\nPeran: ${node.type.toUpperCase()}`;
                group.appendChild(titleElem);

                // Dragging & Selecting Listeners
                group.addEventListener('mousedown', (e) => {
                    e.stopPropagation();
                    draggedNodeId = node.id;
                    const svgRect = svg.getBoundingClientRect();
                    const mouseSvgX = (e.clientX - svgRect.left - panX) / scale;
                    const mouseSvgY = (e.clientY - svgRect.top - panY) / scale;
                    dragOffset.x = mouseSvgX - nodePositions[node.id].x;
                    dragOffset.y = mouseSvgY - nodePositions[node.id].y;
                });

                group.addEventListener('click', (e) => {
                    e.stopPropagation();
                    selectNode(node);
                });

                group.addEventListener('mouseenter', () => {
                    highlightNodeConnections(node.id);
                });
                group.addEventListener('mouseleave', () => {
                    clearHighlight();
                });

                nodesLayer.appendChild(group);
            });
        }

        function setEdgeCurve(pathElem, src, tgt) {
            const dx = tgt.x - src.x;
            const dy = tgt.y - src.y;
            const dist = Math.sqrt(dx * dx + dy * dy);
            const midX = (src.x + tgt.x) / 2;
            const midY = (src.y + tgt.y) / 2;
            const normalX = -dy / dist;
            const normalY = dx / dist;
            const curvature = Math.min(40, dist * 0.15);
            const ctrlX = midX + normalX * curvature;
            const ctrlY = midY + normalY * curvature;

            pathElem.setAttribute('d', `M ${src.x} ${src.y} Q ${ctrlX} ${ctrlY} ${tgt.x} ${tgt.y}`);
        }

        function updateNodeAndEdges(nodeId) {
            const pos = nodePositions[nodeId];
            if (!pos) return;

            const group = document.getElementById(`node-group-${nodeId}`);
            if (group) {
                group.setAttribute('transform', `translate(${pos.x}, ${pos.y})`);
            }

            document.querySelectorAll(`.edge-src-${nodeId}`).forEach(path => {
                const parts = path.id.split('-');
                const tgtId = parts[2];
                const tgtPos = nodePositions[tgtId];
                if (tgtPos) setEdgeCurve(path, pos, tgtPos);
            });

            document.querySelectorAll(`.edge-tgt-${nodeId}`).forEach(path => {
                const parts = path.id.split('-');
                const srcId = parts[1];
                const srcPos = nodePositions[srcId];
                if (srcPos) setEdgeCurve(path, srcPos, pos);
            });
        }

        function highlightNodeConnections(nodeId) {
            const connectedNodeIds = new Set([nodeId]);

            document.querySelectorAll('.graph-edge').forEach(path => {
                const isConnected = path.classList.contains(`edge-src-${nodeId}`) || path.classList.contains(`edge-tgt-${nodeId}`);
                if (isConnected) {
                    path.setAttribute('stroke-opacity', '1');
                    path.setAttribute('stroke-width', '3');
                    const parts = path.id.split('-');
                    connectedNodeIds.add(parseInt(parts[1]));
                    connectedNodeIds.add(parseInt(parts[2]));
                } else {
                    path.setAttribute('stroke-opacity', '0.12');
                }
            });

            document.querySelectorAll('.graph-node-group').forEach(grp => {
                const id = parseInt(grp.id.replace('node-group-', ''));
                if (!connectedNodeIds.has(id)) {
                    grp.setAttribute('opacity', '0.2');
                } else {
                    grp.setAttribute('opacity', '1');
                }
            });
        }

        function clearHighlight() {
            document.querySelectorAll('.graph-edge').forEach(path => {
                const isPillarLink = path.getAttribute('stroke-dasharray') !== null || path.getAttribute('stroke') === '#059669' || path.getAttribute('stroke') === '#10b981';
                path.setAttribute('stroke-opacity', '0.75');
                path.setAttribute('stroke-width', isPillarLink ? '2.4' : '1.5');
            });
            document.querySelectorAll('.graph-node-group').forEach(grp => {
                grp.setAttribute('opacity', '1');
            });
        }

        // Inspector Drawer Logic
        function selectNode(node) {
            activeSelectedNode = node;
            inspectorDrawer.classList.remove('hidden');

            document.getElementById('insp-title').textContent = node.title;
            document.getElementById('insp-slug').textContent = `/artikel/${node.slug}`;
            document.getElementById('insp-category').textContent = node.category;
            document.getElementById('insp-status').textContent = node.status;
            document.getElementById('insp-outgoing-count').textContent = node.outgoing_count || 0;
            document.getElementById('insp-incoming-count').textContent = node.incoming_count || 0;

            const badge = document.getElementById('insp-type-badge');
            const roleSelect = document.getElementById('insp-role-select');
            const parentPillarWrapper = document.getElementById('insp-parent-pillar-wrapper');
            const pillarBox = document.getElementById('insp-pillar-box');

            if (node.is_pillar) {
                badge.className = 'inline-flex rounded-md bg-[#1e3a5f]/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-[#1e3a5f] dark:bg-amber-500/15 dark:text-amber-400';
                badge.textContent = 'Pilar Utama (Hub)';
                roleSelect.value = 'pilar';
                parentPillarWrapper.classList.add('hidden');
                pillarBox.classList.add('hidden');
            } else if (node.pillar_id) {
                roleSelect.value = 'pendukung';
                parentPillarWrapper.classList.remove('hidden');
                document.getElementById('insp-parent-pillar-select').value = node.pillar_id;

                pillarBox.classList.remove('hidden');
                document.getElementById('insp-pillar-title').textContent = node.pillar_title || `Pilar #${node.pillar_id}`;

                const ind = document.getElementById('insp-backlink-indicator');
                const alertBox = document.getElementById('insp-backlink-alert');

                if (node.has_pillar_backlink) {
                    badge.className = 'inline-flex rounded-md bg-emerald-500/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400';
                    badge.textContent = 'Turunan Aman';
                    ind.className = 'inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 dark:text-emerald-400';
                    ind.innerHTML = '<svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg> Terhubung';
                    alertBox.className = 'mt-2 rounded-lg bg-emerald-50/70 p-2 text-[11px] text-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-300';
                    alertBox.textContent = 'Artikel ini telah menautkan kembali ke pilar induk dengan benar.';
                } else {
                    badge.className = 'inline-flex rounded-md bg-amber-500/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400';
                    badge.textContent = 'Perlu Tautan Pilar';
                    ind.className = 'inline-flex items-center gap-1 text-[10px] font-bold text-amber-600 dark:text-amber-400';
                    ind.innerHTML = '<svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/></svg> Hilang';
                    alertBox.className = 'mt-2 rounded-lg bg-amber-50/80 p-2 text-[11px] text-amber-900 dark:bg-amber-950/40 dark:text-amber-200';
                    alertBox.innerHTML = '<strong>Peringatan SEO:</strong> Artikel turunan ini belum memiliki tautan yang mengarah kembali ke pilar induk. Buka editor untuk menyisipkan tautan.';
                }
            } else {
                badge.className = 'inline-flex rounded-md bg-stone-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-stone-600 dark:bg-white/[0.06] dark:text-stone-300';
                badge.textContent = 'Artikel Standar';
                roleSelect.value = 'standar';
                parentPillarWrapper.classList.add('hidden');
                pillarBox.classList.add('hidden');
            }

            document.getElementById('btn-insp-edit').href = node.edit_url;
            document.getElementById('btn-insp-view').href = node.url;
        }

        window.closeInspector = function () {
            inspectorDrawer.classList.add('hidden');
            activeSelectedNode = null;
        };

        document.getElementById('insp-role-select')?.addEventListener('change', function () {
            const parentWrap = document.getElementById('insp-parent-pillar-wrapper');
            if (this.value === 'pendukung') {
                parentWrap.classList.remove('hidden');
            } else {
                parentWrap.classList.add('hidden');
            }
        });

        window.saveInspectorRole = function () {
            if (!activeSelectedNode) return;
            const role = document.getElementById('insp-role-select').value;
            const pillarId = document.getElementById('insp-parent-pillar-select').value;
            performQuickAssign(activeSelectedNode.id, role, pillarId);
        };

        function performQuickAssign(artikelId, role, pillarId) {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch('{{ route("admin.kluster.quick-assign") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    artikel_id: artikelId,
                    role: role,
                    pillar_id: role === 'pendukung' ? pillarId : null,
                }),
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    showToast(res.message);
                    closeInspector();
                    closeQuickAssignModal();
                    loadGraph();
                    setTimeout(() => { window.location.reload(); }, 600);
                } else {
                    alert(res.message || 'Gagal memperbarui peran.');
                }
            })
            .catch(() => {
                alert('Terjadi kesalahan jaringan.');
            });
        }

        window.openQuickAssignModal = function (id, title, role, currentPillarId) {
            document.getElementById('modal-article-id').value = id;
            document.getElementById('modal-assign-title').textContent = title;
            document.getElementById('modal-role-select').value = role;

            const pillarContainer = document.getElementById('modal-pillar-select-container');
            if (role === 'pendukung') {
                pillarContainer.classList.remove('hidden');
                if (currentPillarId) {
                    document.getElementById('modal-pillar-id-select').value = currentPillarId;
                }
            } else {
                pillarContainer.classList.add('hidden');
            }

            document.getElementById('modal-quick-assign').classList.remove('hidden');
        };

        window.closeQuickAssignModal = function () {
            document.getElementById('modal-quick-assign').classList.add('hidden');
        };

        document.getElementById('modal-role-select')?.addEventListener('change', function () {
            const container = document.getElementById('modal-pillar-select-container');
            if (this.value === 'pendukung') {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        });

        window.submitQuickAssign = function (e) {
            e.preventDefault();
            const id = document.getElementById('modal-article-id').value;
            const role = document.getElementById('modal-role-select').value;
            const pillarId = document.getElementById('modal-pillar-id-select').value;
            performQuickAssign(id, role, pillarId);
        };

        // Table Filtering
        const tableSearchInput = document.getElementById('table-search-input');
        const tableRoleFilter = document.getElementById('table-role-filter');

        function filterTable() {
            const q = (tableSearchInput?.value || '').toLowerCase().trim();
            const role = tableRoleFilter?.value || '';

            document.querySelectorAll('#cluster-articles-table .article-row').forEach(row => {
                const title = row.getAttribute('data-title') || '';
                const rowRole = row.getAttribute('data-role') || '';
                const isMissing = row.getAttribute('data-missing') === '1';

                const matchesQuery = !q || title.includes(q);
                let matchesRole = true;

                if (role === 'missing') {
                    matchesRole = isMissing;
                } else if (role) {
                    matchesRole = rowRole === role;
                }

                if (matchesQuery && matchesRole) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        }

        tableSearchInput?.addEventListener('input', filterTable);
        tableRoleFilter?.addEventListener('change', filterTable);

        // Canvas Filters
        selectPillar?.addEventListener('change', loadGraph);
        toggleIssues?.addEventListener('change', loadGraph);

        searchInput?.addEventListener('input', function () {
            const term = this.value.toLowerCase().trim();
            if (!term) {
                clearHighlight();
                return;
            }

            document.querySelectorAll('.graph-node-group').forEach(grp => {
                const id = parseInt(grp.id.replace('node-group-', ''));
                const node = graphData.nodes.find(n => n.id === id);
                if (node && node.title.toLowerCase().includes(term)) {
                    grp.setAttribute('opacity', '1');
                } else {
                    grp.setAttribute('opacity', '0.15');
                }
            });
        });

        function showToast(msg) {
            const toast = document.getElementById('quick-toast');
            const msgEl = document.getElementById('toast-msg');
            msgEl.textContent = msg;
            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }

        // Theme MutationObserver: sync graph styles when user switches theme
        const themeObserver = new MutationObserver(() => {
            if (graphData && graphData.nodes && graphData.nodes.length) {
                renderGraph(graphData);
            }
        });
        themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

        // Initialize Graph on load
        loadGraph();
    });
</script>
@endpush
