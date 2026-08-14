@extends('layouts.admin')

@section('title', 'Penulis')
@section('page-title', 'Penulis')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-stone-500 dark:text-stone-400">Kelola penulis artikel</p>
        </div>
        <a href="{{ route('admin.penulis.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#1e3a5f] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16304a] dark:bg-[#5b9bd5] dark:text-[#0f0f0e] dark:hover:bg-[#7ab3e0]">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
            Tambah Penulis
        </a>
    </div>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    {{-- Cards Grid --}}
    <div class="mt-6">
        @if ($penulis->isEmpty())
            <div class="rounded-2xl border border-dashed border-stone-200 bg-white p-12 text-center dark:border-white/[0.06] dark:bg-[#171716]">
                <svg class="mx-auto h-12 w-12 text-stone-300 dark:text-stone-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <p class="mt-3 text-sm text-stone-500 dark:text-stone-400">Belum ada penulis</p>
                <a href="{{ route('admin.penulis.create') }}" class="mt-3 inline-block text-sm font-semibold text-[#1e3a5f] dark:text-[#5b9bd5]">Tambah penulis baru &rarr;</a>
            </div>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($penulis as $p)
                    <div class="group overflow-hidden rounded-2xl border border-stone-200/60 bg-white transition hover:border-stone-300 dark:border-white/[0.06] dark:bg-[#171716] dark:hover:border-white/10">
                        <div class="p-5">
                            <div class="flex items-start gap-4">
                                <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-stone-100 font-serif text-lg font-bold text-stone-500 dark:bg-white/[0.05] dark:text-stone-400">
                                    {{ substr($p->name, 0, 1) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    @if ($p->penulis)
                                        <a href="{{ route('penulis.show', $p->penulis) }}" target="_blank" class="font-serif text-base font-bold leading-snug group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5] flex items-center gap-1.5">
                                            {{ $p->name }}
                                            <svg class="h-3.5 w-3.5 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                                        </a>
                                    @else
                                        <h3 class="font-serif text-base font-bold leading-snug group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">{{ $p->name }}</h3>
                                    @endif
                                    <p class="mt-0.5 text-xs text-stone-400 dark:text-stone-500">{{ $p->email }}</p>
                                </div>
                            </div>

                            <div class="mt-4 flex items-center justify-between">
                                <div class="flex items-center gap-2 text-[11px] text-stone-400 dark:text-stone-500">
                                    <span class="font-semibold text-stone-600 dark:text-stone-300">{{ $p->artikel_count }}</span>
                                    <span>artikel</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <form method="POST" action="{{ route('admin.penulis.destroy', $p) }}" onsubmit="return confirm('Yakin ingin menghapus penulis ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg p-1.5 text-stone-400 transition hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-500/10 dark:hover:text-red-400" title="Hapus dari penulis">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
