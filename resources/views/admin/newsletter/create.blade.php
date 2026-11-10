@extends('layouts.admin')

@section('title', 'Kirim Buletin')
@section('page-title', 'Kirim Buletin')

@section('content')
    @php
        $label = 'mb-1.5 block text-sm font-semibold text-stone-700 dark:text-stone-300';
        $error = 'mt-1.5 text-xs text-red-500';
        $input = 'w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-2.5 text-sm text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-stone-400 dark:focus:ring-stone-400/20';
    @endphp

    <div class="mx-auto max-w-2xl">
        {{-- Back --}}
        <a href="{{ route('admin.newsletter.index') }}" class="mb-6 inline-flex items-center gap-1.5 text-sm font-medium text-stone-400 transition hover:text-stone-600 dark:hover:text-stone-300">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Kembali
        </a>

        @if (session('success'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-400">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif

        <div class="rounded-2xl border border-stone-200/60 bg-white p-6 dark:border-white/[0.06] dark:bg-[#171716] sm:p-8">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-[#1e3a5f]/10 text-[#1e3a5f] dark:bg-white/10 dark:text-stone-200">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                </div>
                <div>
                    <h3 class="font-serif text-base font-bold text-stone-800 dark:text-stone-200">Tulis Buletin</h3>
                    <p class="mt-0.5 text-xs text-stone-400 dark:text-stone-500">Email akan dikirimkan ke {{ $totalSubscribers }} subscriber via antrean latar belakang.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.newsletter.store') }}" class="mt-6 space-y-5">
                @csrf

                <div>
                    <label for="subject" class="{{ $label }}">Subjek Email</label>
                    <input
                        type="text"
                        id="subject"
                        name="subject"
                        value="{{ old('subject') }}"
                        required
                        maxlength="150"
                        placeholder="Contoh: Mengenal Peradaban Mesir Kuno"
                        class="{{ $input }}"
                    >
                    @error('subject')
                        <p class="{{ $error }}">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message" class="{{ $label }}">Isi Buletin</label>
                    <textarea
                        id="message"
                        name="message"
                        rows="10"
                        required
                        placeholder="Tulis isi buletin di sini..."
                        class="{{ $input }} resize-none"
                    >{{ old('message') }}</textarea>
                    @error('message')
                        <p class="{{ $error }}">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#1e3a5f] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16304a] active:scale-[0.98] dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                        Kirim ke Semua ({{ $totalSubscribers }})
                    </button>
                    <button type="submit" formaction="{{ route('admin.newsletter.test-send') }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm font-medium text-stone-700 transition hover:bg-stone-50 dark:border-white/10 dark:bg-white/[0.04] dark:text-stone-300">
                        <svg class="h-4 w-4 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 13V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h9"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/><path d="m16 19 2 2 4-4"/></svg>
                        Kirim Uji Coba ke Email Saya
                    </button>
                    <a href="{{ route('admin.newsletter.index') }}" class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-medium text-stone-500 hover:text-stone-800 dark:text-stone-400 dark:hover:text-white">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
