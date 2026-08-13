@extends('layouts.admin')

@section('title', 'Artikel')
@section('page-title', 'Artikel')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-stone-500 dark:text-stone-400">Kelola artikel sejarah</p>
        </div>
        <a href="{{ route('admin.artikel.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#1e3a5f] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16304a] dark:bg-[#5b9bd5] dark:text-[#0f0f0e] dark:hover:bg-[#7ab3e0]">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
            Tulis Artikel
        </a>
    </div>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <form method="GET" class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari artikel..."
                class="w-full rounded-xl border border-stone-200 bg-white py-2.5 pl-10 pr-4 text-sm text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-[#171716] dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10"
            >
        </div>
        <div class="flex gap-2">
            <select name="status" class="rounded-xl border border-stone-200 bg-white px-3 py-2.5 text-sm text-stone-600 outline-none transition focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-[#171716] dark:text-stone-400 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10">
                <option value="">Semua Status</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
            <button type="submit" class="rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm font-medium text-stone-600 transition hover:border-stone-300 hover:text-stone-900 dark:border-white/10 dark:bg-[#171716] dark:text-stone-400 dark:hover:border-white/20 dark:hover:text-white">Filter</button>
        </div>
    </form>

    {{-- Cards Grid --}}
    <div class="mt-6">
        @if ($artikels->isEmpty())
            <div class="rounded-2xl border border-dashed border-stone-200 bg-white p-12 text-center dark:border-white/[0.06] dark:bg-[#171716]">
                <svg class="mx-auto h-12 w-12 text-stone-300 dark:text-stone-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                <p class="mt-3 text-sm text-stone-500 dark:text-stone-400">Belum ada artikel</p>
                <a href="{{ route('admin.artikel.create') }}" class="mt-3 inline-block text-sm font-semibold text-[#1e3a5f] dark:text-[#5b9bd5]">Tulis artikel baru &rarr;</a>
            </div>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($artikels as $artikel)
                    <div class="group relative overflow-hidden rounded-2xl border border-stone-200/60 bg-white transition hover:border-stone-300 dark:border-white/[0.06] dark:bg-[#171716] dark:hover:border-white/10">
                        {{-- Thumbnail --}}
                        <div class="aspect-[16/10] overflow-hidden bg-stone-100 dark:bg-white/[0.03]">
                            @if ($artikel->gambar)
                                <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            @else
                                <div class="flex h-full w-full items-center justify-center">
                                    <svg class="h-10 w-10 text-stone-300 dark:text-stone-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                </div>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="p-4">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide
                                    {{ match($artikel->status) {
                                        'published' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
                                        'archived' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                                        default => 'bg-stone-100 text-stone-500 dark:bg-white/[0.05] dark:text-stone-400',
                                    } }}">
                                    {{ $artikel->status }}
                                </span>
                                <span class="text-[11px] text-stone-400 dark:text-stone-500">{{ $artikel->created_at->format('d M Y') }}</span>
                            </div>

                            <h3 class="mt-2 line-clamp-2 font-serif text-base font-bold leading-snug group-hover:text-[#1e3a5f] dark:group-hover:text-[#5b9bd5]">{{ $artikel->judul }}</h3>

                            @if ($artikel->ringkasan)
                                <p class="mt-1.5 line-clamp-2 text-xs text-stone-400 dark:text-stone-500">{{ $artikel->ringkasan }}</p>
                            @endif

                            <div class="mt-3 flex items-center justify-between">
                                <div class="flex items-center gap-2 text-[11px] text-stone-400 dark:text-stone-500">
                                    <span>{{ $artikel->kategori->nama ?? '-' }}</span>
                                    <span>&middot;</span>
                                    <span>{{ number_format($artikel->views) }} dibaca</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.artikel.edit', $artikel) }}" class="rounded-lg p-1.5 text-stone-400 transition hover:bg-stone-100 hover:text-[#1e3a5f] dark:hover:bg-white/[0.05] dark:hover:text-[#5b9bd5]" title="Edit">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.artikel.destroy', $artikel) }}" onsubmit="return confirm('Yakin ingin menghapus artikel ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg p-1.5 text-stone-400 transition hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-500/10 dark:hover:text-red-400" title="Hapus">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $artikels->links() }}
            </div>
        @endif
    </div>
@endsection
