@extends('layouts.admin')

@section('title', 'Detail Pesan Kontak')
@section('page-title', 'Detail Pesan Kontak')

@section('content')
    <div class="mx-auto max-w-3xl">
        {{-- Back --}}
        <a href="{{ route('admin.kontak.index') }}" class="mb-6 inline-flex items-center gap-1.5 text-sm font-medium text-stone-400 transition hover:text-stone-600 dark:hover:text-stone-300">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Kembali ke Pesan Kontak
        </a>

        @if (session('success'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-400">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-2xl border border-stone-200/60 bg-white p-6 dark:border-white/[0.06] dark:bg-[#171716] sm:p-8">
            <div class="flex flex-col gap-4 border-b border-stone-100 pb-6 dark:border-white/[0.06] sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="font-serif text-xl font-bold tracking-tight text-stone-800 dark:text-stone-100">
                        {{ $pesan->subject ?: 'Tanpa Subjek' }}
                    </h2>
                    <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-stone-500 dark:text-stone-400">
                        <span><strong>Dari:</strong> {{ $pesan->name }} ({{ $pesan->email }})</span>
                        <span>&bull;</span>
                        <span>{{ $pesan->created_at->translatedFormat('d F Y, H:i') }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('admin.kontak.toggle-read', $pesan) }}">
                        @csrf
                        <button type="submit" class="rounded-xl border border-stone-200 bg-white px-3 py-1.5 text-xs font-medium text-stone-600 transition hover:border-stone-300 dark:border-white/10 dark:bg-[#171716] dark:text-stone-300">
                            {{ $pesan->is_read ? 'Tandai Belum Dibaca' : 'Tandai Sudah Dibaca' }}
                        </button>
                    </form>
                    <a href="mailto:{{ $pesan->email }}?subject=Re: {{ urlencode($pesan->subject ?: 'Pesan Kontak Look at History') }}" class="rounded-xl bg-[#1e3a5f] px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-[#16304a] dark:bg-[#5b9bd5] dark:text-[#0f0f0e]">
                        Balas Email
                    </a>
                </div>
            </div>

            <div class="mt-6">
                <p class="text-xs font-semibold uppercase tracking-wider text-stone-400 dark:text-stone-500">Isi Pesan:</p>
                <div class="mt-3 whitespace-pre-wrap rounded-xl bg-stone-50 p-5 text-sm leading-relaxed text-stone-700 dark:bg-white/[0.02] dark:text-stone-300">
                    {{ $pesan->message }}
                </div>
            </div>

            <div class="mt-8 flex items-center justify-between border-t border-stone-100 pt-6 text-xs text-stone-400 dark:border-white/[0.06]">
                <span>IP Pengirim: {{ $pesan->ip_address ?: '-' }}</span>
                <form method="POST" action="{{ route('admin.kontak.destroy', $pesan) }}" onsubmit="return confirm('Hapus pesan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="font-medium text-red-500 hover:text-red-600">
                        Hapus Pesan
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
