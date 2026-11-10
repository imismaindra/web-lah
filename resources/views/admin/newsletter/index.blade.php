@extends('layouts.admin')

@section('title', 'Buletin')
@section('page-title', 'Buletin')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-stone-500 dark:text-stone-400">Kelola subscriber dan kirim buletin via antrean asinkron</p>
        </div>
        <a href="{{ route('admin.newsletter.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#1e3a5f] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16304a] dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
            Tulis Buletin Baru
        </a>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="mt-6 grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
            <p class="text-sm font-medium text-stone-500 dark:text-stone-400">Total Subscriber Aktif</p>
            <p class="mt-2 font-serif text-3xl font-bold tracking-tight">{{ $totalSubscribers }}</p>
        </div>
        <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
            <p class="text-sm font-medium text-stone-500 dark:text-stone-400">Kampanye Terkirim</p>
            <p class="mt-2 font-serif text-3xl font-bold tracking-tight">{{ $campaigns->total() }}</p>
        </div>
        <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
            <p class="text-sm font-medium text-stone-500 dark:text-stone-400">Metode Pengiriman</p>
            <p class="mt-2 text-sm font-semibold text-emerald-600 dark:text-emerald-400">Antrean Asinkron (Queue Chunk 50)</p>
            <p class="mt-1 text-[11px] text-stone-400">Aman dari HTTP timeout</p>
        </div>
    </div>

    {{-- Riwayat Kampanye --}}
    <div class="mt-8">
        <h3 class="font-serif text-lg font-bold tracking-tight text-stone-800 dark:text-stone-200">Riwayat Kampanye Terkirim</h3>
        <div class="mt-3 overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-stone-200 dark:divide-white/[0.06]">
                    <thead>
                        <tr class="bg-stone-50 text-left text-[11px] font-bold uppercase tracking-wider text-stone-400 dark:bg-white/[0.02] dark:text-stone-500">
                            <th class="px-5 py-3.5">Subjek</th>
                            <th class="px-5 py-3.5">Pengirim</th>
                            <th class="px-5 py-3.5">Penerima</th>
                            <th class="px-5 py-3.5">Waktu Kirim</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200 dark:divide-white/[0.06]">
                        @forelse ($campaigns as $campaign)
                            <tr class="transition hover:bg-stone-50/60 dark:hover:bg-white/[0.02]">
                                <td class="px-5 py-4">
                                    <p class="text-sm font-semibold text-stone-800 dark:text-stone-200">{{ $campaign->subject }}</p>
                                    <p class="line-clamp-1 mt-0.5 text-xs text-stone-400">{{ Str::limit(strip_tags($campaign->message), 80) }}</p>
                                </td>
                                <td class="px-5 py-4 text-xs text-stone-600 dark:text-stone-400">
                                    {{ $campaign->sender->name ?? 'Admin' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-xs font-medium text-stone-700 dark:text-stone-300">
                                    {{ $campaign->total_recipients }} subscriber
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-xs text-stone-400">
                                    {{ $campaign->sent_at ? $campaign->sent_at->translatedFormat('d M Y, H:i') : 'Dalam Antrean' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-sm text-stone-400">Belum ada riwayat kampanye buletin.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($campaigns->hasPages())
                <div class="border-t border-stone-200/60 p-4 dark:border-white/[0.06]">
                    {{ $campaigns->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Daftar Subscriber --}}
    <div class="mt-8">
        <h3 class="font-serif text-lg font-bold tracking-tight text-stone-800 dark:text-stone-200">Daftar Subscriber Aktif</h3>
        <div class="mt-3 overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-stone-200 dark:divide-white/[0.06]">
                    <thead>
                        <tr class="bg-stone-50 text-left text-[11px] font-bold uppercase tracking-wider text-stone-400 dark:bg-white/[0.02] dark:text-stone-500">
                            <th class="px-5 py-3.5">Email</th>
                            <th class="px-5 py-3.5">Berlangganan Sejak</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200 dark:divide-white/[0.06]">
                        @forelse ($subscribers as $subscriber)
                            <tr class="transition hover:bg-stone-50/60 dark:hover:bg-white/[0.02]">
                                <td class="px-5 py-4 text-sm font-semibold">{{ $subscriber->email }}</td>
                                <td class="px-5 py-4 text-sm text-stone-500 dark:text-stone-400">{{ $subscriber->created_at->translatedFormat('d M Y') }}</td>
                                <td class="px-5 py-4 text-right">
                                    <form method="POST" action="{{ route('admin.newsletter.destroy', $subscriber) }}" onsubmit="return confirm('Hapus subscriber {{ $subscriber->email }}?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg p-1.5 text-stone-400 transition hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-500/10 dark:hover:text-red-400" title="Hapus subscriber">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-12 text-center text-sm text-stone-400 dark:text-stone-500">Belum ada subscriber</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($subscribers->hasPages())
                <div class="border-t border-stone-200/60 p-4 dark:border-white/[0.06]">
                    {{ $subscribers->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
