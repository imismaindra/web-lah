@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const getChartTheme = () => {
            const isDark = document.documentElement.classList.contains('dark');
            return {
                lineColor: isDark ? '#d4a373' : '#1e3a5f',
                areaColor: isDark ? 'rgba(212, 163, 115, 0.12)' : 'rgba(30, 58, 95, 0.08)',
                barColor: isDark ? 'rgba(212, 163, 115, 0.85)' : 'rgba(30, 58, 95, 0.8)',
                gridColor: isDark ? 'rgba(255, 255, 255, 0.06)' : 'rgba(0, 0, 0, 0.05)',
                tickColor: isDark ? '#a8a29e' : '#78716c',
            };
        };

        let theme = getChartTheme();
        let viewsChartInstance = null;
        let articlesChartInstance = null;

        // Views Chart
        const viewsCtx = document.getElementById('viewsChart');
        if (viewsCtx) {
            viewsChartInstance = new Chart(viewsCtx, {
                type: 'line',
                data: {
                    labels: @json(array_column($viewsChart, 'date')),
                    datasets: [{
                        label: 'Views',
                        data: @json(array_column($viewsChart, 'views')),
                        borderColor: theme.lineColor,
                        backgroundColor: theme.areaColor,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: theme.gridColor },
                            ticks: { color: theme.tickColor, font: { family: 'Plus Jakarta Sans, sans-serif', size: 11 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: theme.tickColor, font: { family: 'Plus Jakarta Sans, sans-serif', size: 11 } }
                        }
                    }
                }
            });
        }

        // Articles Chart
        const articlesCtx = document.getElementById('articlesChart');
        if (articlesCtx) {
            articlesChartInstance = new Chart(articlesCtx, {
                type: 'bar',
                data: {
                    labels: @json(array_column($articlesChart, 'month')),
                    datasets: [{
                        label: 'Artikel',
                        data: @json(array_column($articlesChart, 'count')),
                        backgroundColor: theme.barColor,
                        borderRadius: 6,
                        maxBarThickness: 40,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: theme.gridColor },
                            ticks: { color: theme.tickColor, font: { family: 'Plus Jakarta Sans, sans-serif', size: 11 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: theme.tickColor, font: { family: 'Plus Jakarta Sans, sans-serif', size: 11 } }
                        }
                    }
                }
            });
        }

        const updateChartsTheme = () => {
            const next = getChartTheme();
            if (viewsChartInstance) {
                viewsChartInstance.data.datasets[0].borderColor = next.lineColor;
                viewsChartInstance.data.datasets[0].backgroundColor = next.areaColor;
                viewsChartInstance.options.scales.y.grid.color = next.gridColor;
                viewsChartInstance.options.scales.y.ticks.color = next.tickColor;
                viewsChartInstance.options.scales.x.ticks.color = next.tickColor;
                viewsChartInstance.update();
            }
            if (articlesChartInstance) {
                articlesChartInstance.data.datasets[0].backgroundColor = next.barColor;
                articlesChartInstance.options.scales.y.grid.color = next.gridColor;
                articlesChartInstance.options.scales.y.ticks.color = next.tickColor;
                articlesChartInstance.options.scales.x.ticks.color = next.tickColor;
                articlesChartInstance.update();
            }
        };

        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                setTimeout(updateChartsTheme, 50);
            });
        }
    });
</script>
@endpush

@section('content')
    {{-- Stats --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-stone-500 dark:text-stone-400">Total Artikel</p>
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#1e3a5f]/10 dark:bg-white/10">
                    <svg class="h-4 w-4 text-[#1e3a5f] dark:text-stone-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                </div>
            </div>
            <p class="mt-3 font-serif text-3xl font-bold tracking-tight">{{ $totalArtikel }}</p>
            <p class="mt-1 text-xs text-stone-400 dark:text-stone-500">
                @php
                    $diff = $totalArtikelBulanIni - $totalArtikelBulanLalu;
                    $sign = $diff >= 0 ? '+' : '';
                @endphp
                <span class="{{ $diff >= 0 ? 'text-emerald-500' : 'text-red-500' }}">{{ $sign }}{{ $diff }}</span> vs bulan lalu
            </p>
        </div>

        <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-stone-500 dark:text-stone-400">Total Dibaca</p>
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500/10">
                    <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                </div>
            </div>
            <p class="mt-3 font-serif text-3xl font-bold tracking-tight">{{ number_format($totalViews) }}</p>
            <p class="mt-1 text-xs text-stone-400 dark:text-stone-500">
                @php
                    $viewsDiff = $viewsBulanIni - $viewsBulanLalu;
                    $viewsSign = $viewsDiff >= 0 ? '+' : '';
                @endphp
                <span class="{{ $viewsDiff >= 0 ? 'text-emerald-500' : 'text-red-500' }}">{{ $viewsSign }}{{ number_format($viewsDiff) }}</span> vs bulan lalu
            </p>
        </div>

        <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-stone-500 dark:text-stone-400">Kategori</p>
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-500/10">
                    <svg class="h-4 w-4 text-amber-600 dark:text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10h10V2Z"/><path d="M22 12H12v10h10V12Z"/><path d="M22 2H12v5h10V2Z"/><path d="M7 12H2v10h5V12Z"/></svg>
                </div>
            </div>
            <p class="mt-3 font-serif text-3xl font-bold tracking-tight">{{ $totalKategori }}</p>
            <p class="mt-1 text-xs text-stone-400 dark:text-stone-500">Aktif</p>
        </div>

        <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-stone-500 dark:text-stone-400">Penulis</p>
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-500/10">
                    <svg class="h-4 w-4 text-violet-600 dark:text-violet-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
            </div>
            <p class="mt-3 font-serif text-3xl font-bold tracking-tight">{{ $totalPenulis }}</p>
            <p class="mt-1 text-xs text-stone-400 dark:text-stone-500">Terdaftar</p>
        </div>
    </div>

    @if ($pendingKomentars > 0 || $unreadPesan > 0)
        <div class="mt-4 flex flex-wrap gap-3">
            @if ($pendingKomentars > 0)
                <a href="{{ route('admin.komentar.index', ['status' => 'pending']) }}" class="inline-flex items-center gap-2 rounded-xl border border-amber-200 bg-amber-50 px-4 py-2 text-xs font-semibold text-amber-800 transition hover:bg-amber-100 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-300">
                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                    {{ $pendingKomentars }} komentar baru menunggu persetujuan &rarr;
                </a>
            @endif
            @if ($unreadPesan > 0)
                <a href="{{ route('admin.kontak.index', ['status' => 'unread']) }}" class="inline-flex items-center gap-2 rounded-xl border border-sky-200 bg-sky-50 px-4 py-2 text-xs font-semibold text-sky-800 transition hover:bg-sky-100 dark:border-sky-500/20 dark:bg-sky-500/10 dark:text-sky-300">
                    <span class="h-2 w-2 rounded-full bg-sky-500"></span>
                    {{ $unreadPesan }} pesan kontak baru belum dibaca &rarr;
                </a>
            @endif
        </div>
    @endif

    {{-- Main Dashboard Layout --}}
    <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_340px]">
        {{-- Main Column: Charts + Recent Articles --}}
        <div class="space-y-6">
            {{-- Charts --}}
            <div class="grid gap-6 sm:grid-cols-2">
                {{-- Views Trend --}}
                <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
                    <h3 class="mb-4 font-serif text-base font-bold">Views 7 Hari Terakhir</h3>
                    <div class="h-48">
                        <canvas id="viewsChart"></canvas>
                    </div>
                </div>

                {{-- Articles per Month --}}
                <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
                    <h3 class="mb-4 font-serif text-base font-bold">Artikel per Bulan (6 Bulan)</h3>
                    <div class="h-48">
                        <canvas id="articlesChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Recent Articles --}}
            <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
                <div class="flex items-center justify-between">
                    <h2 class="font-serif text-base font-bold tracking-tight">Artikel Terbaru</h2>
                    <a href="{{ route('admin.artikel.index') }}" class="text-xs font-semibold text-[#1e3a5f] transition hover:text-[#16304a] dark:text-stone-300 dark:hover:text-white">Lihat semua &rarr;</a>
                </div>

                <div class="mt-4 space-y-3">
                    @forelse ($recentArtikel as $artikel)
                        <a href="{{ route('admin.artikel.edit', $artikel->id) }}" class="group flex items-center gap-4 rounded-xl border border-stone-100 bg-stone-50/50 p-3.5 transition hover:border-stone-200 hover:bg-stone-50 dark:border-white/[0.04] dark:bg-white/[0.02] dark:hover:border-white/10 dark:hover:bg-white/[0.04]">
                            <div class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-lg bg-stone-100 dark:bg-white/[0.03]">
                                @if ($artikel->gambar)
                                    <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center">
                                        <svg class="h-6 w-6 text-stone-300 dark:text-stone-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="truncate text-sm font-semibold group-hover:text-[#1e3a5f] dark:group-hover:text-white">{{ $artikel->judul }}</p>
                                <div class="mt-1 flex items-center gap-2 text-xs text-stone-400 dark:text-stone-500">
                                    <span>{{ $artikel->kategori->nama ?? 'Tanpa Kategori' }}</span>
                                    <span>·</span>
                                    <span>{{ $artikel->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <span class="shrink-0 rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide
                                {{ $artikel->status === 'published'
                                    ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400'
                                    : 'bg-stone-100 text-stone-500 dark:bg-white/[0.05] dark:text-stone-400' }}">
                                {{ $artikel->status }}
                            </span>
                        </a>
                    @empty
                        <div class="p-8 text-center">
                            <svg class="mx-auto h-10 w-10 text-stone-300 dark:text-stone-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                            <p class="mt-3 text-sm text-stone-500 dark:text-stone-400">Belum ada artikel</p>
                            <a href="{{ route('admin.artikel.create') }}" class="mt-3 inline-block text-sm font-semibold text-[#1e3a5f] dark:text-stone-300 dark:hover:text-white">Tulis artikel baru &rarr;</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar: Quick Actions + Popular Articles --}}
        <div class="space-y-6">
            {{-- Quick Actions --}}
            <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
                <h3 class="mb-4 font-serif text-base font-bold">Aksi Cepat</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.artikel.create') }}" class="flex items-center gap-3 rounded-xl border border-stone-100 px-4 py-3 text-sm font-medium text-stone-600 transition hover:border-[#1e3a5f]/20 hover:bg-[#1e3a5f]/5 hover:text-[#1e3a5f] dark:border-white/[0.04] dark:text-stone-400 dark:hover:border-white/20 dark:hover:bg-white/[0.06] dark:hover:text-white">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                        Tulis Artikel Baru
                    </a>
                    @if (auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.kategori.create') }}" class="flex items-center gap-3 rounded-xl border border-stone-100 px-4 py-3 text-sm font-medium text-stone-600 transition hover:border-[#1e3a5f]/20 hover:bg-[#1e3a5f]/5 hover:text-[#1e3a5f] dark:border-white/[0.04] dark:text-stone-400 dark:hover:border-white/20 dark:hover:bg-white/[0.06] dark:hover:text-white">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10h10V2Z"/><path d="M22 12H12v10h10V12Z"/><path d="M22 2H12v5h10V2Z"/><path d="M7 12H2v10h5V12Z"/></svg>
                            Tambah Kategori
                        </a>
                    @endif
                </div>
            </div>

            {{-- Popular Articles --}}
            <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
                <h3 class="mb-4 font-serif text-base font-bold">Paling Dibaca</h3>
                <ul class="space-y-3">
                    @forelse ($popularArtikel as $index => $artikel)
                        <li>
                            <a href="{{ route('admin.artikel.edit', $artikel->id) }}" class="group flex gap-3">
                                <span class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-md bg-stone-100 text-[10px] font-bold text-stone-400 group-hover:bg-[#1e3a5f] group-hover:text-white dark:bg-white/[0.05] dark:text-stone-500 dark:group-hover:bg-white dark:group-hover:text-stone-900">{{ $index + 1 }}</span>
                                <div>
                                    <p class="text-sm font-medium leading-snug group-hover:text-[#1e3a5f] dark:group-hover:text-white">{{ $artikel->judul }}</p>
                                    <p class="mt-0.5 text-[11px] text-stone-400 dark:text-stone-500">{{ number_format($artikel->views) }} dibaca</p>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li class="py-4 text-center text-sm text-stone-400 dark:text-stone-500">Belum ada data</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
