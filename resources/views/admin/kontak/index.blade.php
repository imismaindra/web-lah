@extends('layouts.admin')

@section('title', 'Pesan Kontak')
@section('page-title', 'Pesan Kontak')

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-stone-500 dark:text-stone-400">Pesan masuk dari formulir kontak pengunjung situs</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter --}}
    <form method="GET" class="mt-6 flex flex-wrap items-center gap-2">
        <select name="status" class="rounded-xl border border-stone-200 bg-white px-3 py-2 text-sm text-stone-600 outline-none transition focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-[#171716] dark:text-stone-400 dark:focus:border-[#5b9bd5]">
            <option value="">Semua Pesan</option>
            <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Belum Dibaca ({{ $unreadCount }})</option>
            <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
        </select>
        <button type="submit" class="rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-600 transition hover:border-stone-300 hover:text-stone-900 dark:border-white/10 dark:bg-[#171716] dark:text-stone-400 dark:hover:text-white">Filter</button>
    </form>

    {{-- Table --}}
    <div class="mt-6 overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-stone-200 dark:divide-white/[0.06]">
                <thead>
                    <tr class="bg-stone-50 text-left text-[11px] font-bold uppercase tracking-wider text-stone-400 dark:bg-white/[0.02] dark:text-stone-500">
                        <th class="px-5 py-3.5">Pengirim</th>
                        <th class="px-5 py-3.5">Subjek & Pesan</th>
                        <th class="px-5 py-3.5">Waktu</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 dark:divide-white/[0.06]">
                    @forelse ($pesans as $pesan)
                        <tr class="transition hover:bg-stone-50/60 dark:hover:bg-white/[0.02] {{ !$pesan->is_read ? 'bg-amber-50/30 dark:bg-amber-500/[0.03]' : '' }}">
                            <td class="px-5 py-4">
                                <p class="text-sm font-semibold text-stone-800 dark:text-stone-200">{{ $pesan->name }}</p>
                                <a href="mailto:{{ $pesan->email }}" class="text-xs text-stone-400 hover:text-[#1e3a5f] dark:hover:text-[#5b9bd5]">{{ $pesan->email }}</a>
                            </td>
                            <td class="max-w-md px-5 py-4">
                                <a href="{{ route('admin.kontak.show', $pesan) }}" class="block">
                                    <p class="text-sm font-medium text-stone-800 hover:text-[#1e3a5f] dark:text-stone-200 dark:hover:text-[#5b9bd5]">
                                        {{ $pesan->subject ?: 'Tanpa Subjek' }}
                                    </p>
                                    <p class="line-clamp-1 text-xs text-stone-500 dark:text-stone-400">{{ $pesan->message }}</p>
                                </a>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-xs text-stone-400">
                                {{ $pesan->created_at->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-xs">
                                @if (!$pesan->is_read)
                                    <span class="rounded-full bg-amber-100 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-amber-700 dark:bg-amber-500/20 dark:text-amber-400">Baru</span>
                                @else
                                    <span class="rounded-full bg-stone-100 px-2.5 py-0.5 text-[10px] font-medium text-stone-600 dark:bg-white/[0.06] dark:text-stone-400">Dibaca</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.kontak.show', $pesan) }}" class="rounded-lg p-1.5 text-stone-400 hover:bg-stone-100 hover:text-stone-700 dark:hover:bg-white/[0.06] dark:hover:text-stone-300" title="Buka Pesan">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.kontak.destroy', $pesan) }}" onsubmit="return confirm('Hapus pesan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg p-1.5 text-stone-400 hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-500/10" title="Hapus">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-sm text-stone-400">
                                Tidak ada pesan kontak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pesans->hasPages())
            <div class="border-t border-stone-200/60 p-4 dark:border-white/[0.06]">
                {{ $pesans->links() }}
            </div>
        @endif
    </div>
@endsection
