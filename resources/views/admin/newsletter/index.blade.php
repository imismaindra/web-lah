@extends('layouts.admin')

@section('title', 'Buletin')
@section('page-title', 'Buletin')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-stone-500 dark:text-stone-400">Kelola subscriber dan kirim buletin</p>
        </div>
        <a href="{{ route('admin.newsletter.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#1e3a5f] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16304a] dark:bg-[#5b9bd5] dark:text-[#0f0f0e] dark:hover:bg-[#7ab3e0]">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
            Kirim Buletin
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
            <p class="text-sm font-medium text-stone-500 dark:text-stone-400">Total Subscriber</p>
            <p class="mt-2 font-serif text-3xl font-bold tracking-tight">{{ $subscribers->count() }}</p>
        </div>
        <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
            <p class="text-sm font-medium text-stone-500 dark:text-stone-400">Berlangganan Minggu Ini</p>
            <p class="mt-2 font-serif text-3xl font-bold tracking-tight">{{ $subscribers->where('created_at', '>=', now()->startOfWeek())->count() }}</p>
        </div>
        <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
            <p class="text-sm font-medium text-stone-500 dark:text-stone-400">Batas Gmail / Hari</p>
            <p class="mt-2 font-serif text-3xl font-bold tracking-tight">500</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="mt-6 overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
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
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end">
                                    <form method="POST" action="{{ route('admin.newsletter.destroy', $subscriber) }}" onsubmit="return confirm('Hapus subscriber {{ $subscriber->email }}?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg p-1.5 text-stone-400 transition hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-500/10 dark:hover:text-red-400" title="Hapus subscriber">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
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
    </div>
@endsection