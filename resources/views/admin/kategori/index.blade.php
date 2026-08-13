@extends('layouts.admin')

@section('title', 'Kategori')
@section('page-title', 'Kategori')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-stone-500 dark:text-stone-400">Kelola kategori artikel</p>
        </div>
        <a href="{{ route('admin.kategori.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#1e3a5f] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16304a] dark:bg-[#5b9bd5] dark:text-[#0f0f0e] dark:hover:bg-[#7ab3e0]">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
            Tambah Kategori
        </a>
    </div>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="mt-6 overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
        @if ($kategoris->isEmpty())
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-stone-300 dark:text-stone-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10h10V2Z"/><path d="M22 12H12v10h10V12Z"/><path d="M22 2H12v5h10V2Z"/><path d="M7 12H2v10h5V12Z"/></svg>
                <p class="mt-3 text-sm text-stone-500 dark:text-stone-400">Belum ada kategori</p>
                <a href="{{ route('admin.kategori.create') }}" class="mt-3 inline-block text-sm font-semibold text-[#1e3a5f] dark:text-[#5b9bd5]">Tambah kategori baru &rarr;</a>
            </div>
        @else
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-stone-100 dark:border-white/[0.04]">
                        <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-stone-400 dark:text-stone-500">Nama</th>
                        <th class="hidden px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-stone-400 dark:text-stone-500 sm:table-cell">Slug</th>
                        <th class="hidden px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-stone-400 dark:text-stone-500 md:table-cell">Artikel</th>
                        <th class="hidden px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-stone-400 dark:text-stone-500 lg:table-cell">Dibuat</th>
                        <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-stone-400 dark:text-stone-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 dark:divide-white/[0.04]">
                    @foreach ($kategoris as $kategori)
                        <tr class="group transition hover:bg-stone-50/50 dark:hover:bg-white/[0.01]">
                            <td class="px-5 py-4">
                                <div>
                                    <p class="font-semibold text-stone-800 dark:text-stone-200">{{ $kategori->nama }}</p>
                                    @if ($kategori->deskripsi)
                                        <p class="mt-0.5 max-w-md truncate text-xs text-stone-400 dark:text-stone-500">{{ $kategori->deskripsi }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="hidden px-5 py-4 sm:table-cell">
                                <code class="rounded-md bg-stone-100 px-2 py-1 text-xs text-stone-500 dark:bg-white/[0.05] dark:text-stone-400">{{ $kategori->slug }}</code>
                            </td>
                            <td class="hidden px-5 py-4 md:table-cell">
                                <span class="inline-flex items-center gap-1.5 text-sm text-stone-600 dark:text-stone-400">
                                    <span class="font-semibold">{{ $kategori->artikel_count }}</span>
                                    <span class="text-stone-400 dark:text-stone-500">artikel</span>
                                </span>
                            </td>
                            <td class="hidden px-5 py-4 lg:table-cell">
                                <span class="text-xs text-stone-400 dark:text-stone-500">{{ $kategori->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.kategori.edit', $kategori) }}" class="rounded-lg p-2 text-stone-400 transition hover:bg-stone-100 hover:text-[#1e3a5f] dark:hover:bg-white/[0.05] dark:hover:text-[#5b9bd5]" title="Edit">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.kategori.destroy', $kategori) }}" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg p-2 text-stone-400 transition hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-500/10 dark:hover:text-red-400" title="Hapus">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
