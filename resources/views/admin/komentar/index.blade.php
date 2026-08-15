@extends('layouts.admin')

@section('title', 'Komentar')
@section('page-title', 'Komentar')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-stone-500 dark:text-stone-400">Kelola komentar pengunjung pada artikel</p>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter --}}
    <form method="GET" class="mt-6">
        <div class="flex gap-2">
            <select name="status" class="rounded-xl border border-stone-200 bg-white px-3 py-2.5 text-sm text-stone-600 outline-none transition focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-[#171716] dark:text-stone-400 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10">
                <option value="">Semua Status</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
            <button type="submit" class="rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm font-medium text-stone-600 transition hover:border-stone-300 hover:text-stone-900 dark:border-white/10 dark:bg-[#171716] dark:text-stone-400 dark:hover:border-white/20 dark:hover:text-white">Filter</button>
        </div>
    </form>

    {{-- Table --}}
    <div class="mt-6 overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-stone-200 dark:divide-white/[0.06]">
                <thead>
                    <tr class="bg-stone-50 text-left text-[11px] font-bold uppercase tracking-wider text-stone-400 dark:bg-white/[0.02] dark:text-stone-500">
                        <th class="px-5 py-3.5">Komentar</th>
                        <th class="px-5 py-3.5">Nama</th>
                        <th class="px-5 py-3.5">Artikel</th>
                        <th class="px-5 py-3.5">Waktu</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 dark:divide-white/[0.06]">
                    @forelse ($komentars as $komentar)
                        <tr class="transition hover:bg-stone-50/60 dark:hover:bg-white/[0.02]">
                            <td class="max-w-xs px-5 py-4">
                                <p class="line-clamp-2 text-sm text-stone-700 dark:text-stone-300">{{ $komentar->isi }}</p>
                                @if ($komentar->parent_id)
                                    <p class="mt-1 text-[11px] font-semibold text-stone-400 dark:text-stone-500">Balasan</p>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-sm font-semibold">{{ $komentar->displayName() }}</td>
                            <td class="max-w-[180px] px-5 py-4">
                                @if ($komentar->artikel)
                                    <a href="{{ route('artikel.show', $komentar->artikel) }}" target="_blank" class="line-clamp-1 text-sm text-[#1e3a5f] transition hover:text-[#16304a] dark:text-[#5b9bd5] dark:hover:text-[#7ab3e0]">{{ $komentar->artikel->judul }}</a>
                                @else
                                    <span class="text-sm text-stone-400">Artikel dihapus</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-sm text-stone-500 dark:text-stone-400">{{ $komentar->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end">
                                    <form method="POST" action="{{ route('admin.komentar.destroy', $komentar) }}" onsubmit="return confirm('Yakin ingin menghapus komentar ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg p-1.5 text-stone-400 transition hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-500/10 dark:hover:text-red-400" title="Hapus komentar">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-sm text-stone-400 dark:text-stone-500">Belum ada komentar</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if ($komentars->hasPages())
        <div class="mt-8">
            {{ $komentars->links() }}
        </div>
    @endif
@endsection