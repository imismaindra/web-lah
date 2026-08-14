@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Views Chart
        const viewsCtx = document.getElementById('viewsChart');
        if (viewsCtx) {
            new Chart(viewsCtx, {
                type: 'line',
                data: {
                    labels: @json(array_column($viewsChart, 'date')),
                    datasets: [{
                        label: 'Views',
                        data: @json(array_column($viewsChart, 'views')),
                        borderColor: '#1e3a5f',
                        backgroundColor: 'rgba(30, 58, 95, 0.1)',
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
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // Articles Chart
        const articlesCtx = document.getElementById('articlesChart');
        if (articlesCtx) {
            new Chart(articlesCtx, {
                type: 'bar',
                data: {
                    labels: @json(array_column($articlesChart, 'month')),
                    datasets: [{
                        label: 'Artikel',
                        data: @json(array_column($articlesChart, 'count')),
                        backgroundColor: 'rgba(30, 58, 95, 0.8)',
                        borderRadius: 6,
                        maxBarThickness: 40,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                        x: { grid: { display: false } }
                    }
                }
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
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#1e3a5f]/10 dark:bg-[#5b9bd5]/10">
                    <svg class="h-4 w-4 text-[#1e3a5f] dark:text-[#5b9bd5]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
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

    {{-- Recent Articles + Charts --}}
    <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_340px]">
        {{-- Charts --}}
        <div class="lg:col-span-1 space-y-6">
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
        <div class="lg:col-span-1">
            <div class="flex items-center justify-between">
                <h2 class="font-serif text-lg font-bold tracking-tight">Artikel Terbaru</h2>
                <a href="{{ route('admin.artikel.index') }}" class="text-xs font-semibold text-[#1e3a5f] transition hover:text-[#16304a] dark:text-[#5b9bd5] dark:hover:text-[#7ab3e0]">Lihat semua &rarr;</a>
            </div>

            <div class="mt-4 space-y-3">
                @forelse ($recentArtikel as $artikel)
                    <a href="{{ route('admin.artikel.edit', $artikel->id) }}" class="group flex items-center gap-4 rounded-2xl border border-stone-200/60 bg-white p-4 transition hover:border-stone-300 dark:border-white/[0.06] dark:bg-[#171716] dark:hover:border-white/10">
                        <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-xl bg-stone-100 dark:bg-white/[0.03]">
                            @if ($artikel->gambar)
                                <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full w-full items-center justify-center">
                                    <svg class="h-8 w-8 text-stone-300 dark:text-stone-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <p class="truncate text-sm font-semibold group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">{{ $artikel->judul }}</p>
                            <div class="mt-1 flex items-center gap-2 text-xs text-stone-400 dark:text-stone-500">
                                <span>{{ $artikel->kategori->nama ?? 'Tanpa Kategori' }}</span>
                                <span>·</span>
                                <span>{{ $artikel->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <span class="rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide
                            {{ $artikel->status === 'published'
                                ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400'
                                : 'bg-stone-100 text-stone-500 dark:bg-white/[0.05] dark:text-stone-400' }}">
                            {{ $artikel->status }}
                        </span>
                    </a>
                @empty
                    <div class="rounded-2xl border border-dashed border-stone-200 bg-white p-8 text-center dark:border-white/[0.06] dark:bg-[#171716]">
                        <svg class="mx-auto h-10 w-10 text-stone-300 dark:text-stone-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                        <p class="mt-3 text-sm text-stone-500 dark:text-stone-400">Belum ada artikel</p>
                        <a href="{{ route('admin.artikel.create') }}" class="mt-3 inline-block text-sm font-semibold text-[#1e3a5f] dark:text-[#5b9bd5]">Tulis artikel baru &rarr;</a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Quick Actions --}}
            <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
                <h3 class="mb-4 font-serif text-base font-bold">Aksi Cepat</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.artikel.create') }}" class="flex items-center gap-3 rounded-xl border border-stone-100 px-4 py-3 text-sm font-medium text-stone-600 transition hover:border-[#1e3a5f]/20 hover:bg-[#1e3a5f]/5 hover:text-[#1e3a5f] dark:border-white/[0.04] dark:text-stone-400 dark:hover:border-[#5b9bd5]/20 dark:hover:bg-[#5b9bd5]/5 dark:hover:text-[#5b9bd5]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                        Tulis Artikel Baru
                    </a>
                    <a href="{{ route('admin.kategori.create') }}" class="flex items-center gap-3 rounded-xl border border-stone-100 px-4 py-3 text-sm font-medium text-stone-600 transition hover:border-[#1e3a5f]/20 hover:bg-[#1e3a5f]/5 hover:text-[#1e3a5f] dark:border-white/[0.04] dark:text-stone-400 dark:hover:border-[#5b9bd5]/20 dark:hover:bg-[#5b9bd5]/5 dark:hover:text-[#5b9bd5]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10h10V2Z"/><path d="M22 12H12v10h10V12Z"/><path d="M22 2H12v5h10V2Z"/><path d="M7 12H2v10h5V12Z"/></svg>
                        Tambah Kategori
                    </a>
                </div>
            </div>

            {{-- Popular Articles --}}
            <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
                <h3 class="mb-4 font-serif text-base font-bold">Paling Dibaca</h3>
                <ul class="space-y-3">
                    @forelse ($popularArtikel as $index => $artikel)
                        <li>
                            <a href="{{ route('admin.artikel.edit', $artikel->id) }}" class="group flex gap-3">
                                <span class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-md bg-stone-100 text-[10px] font-bold text-stone-400 group-hover:bg-[#1e3a5f] group-hover:text-white dark:bg-white/[0.05] dark:text-stone-500 dark:group-hover:bg-[#5b9bd5] dark:group-hover:text-[#0f0f0e]">{{ $index + 1 }}</span>
                                <div>
                                    <p class="text-sm font-medium leading-snug group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">{{ $artikel->judul }}</p>
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
