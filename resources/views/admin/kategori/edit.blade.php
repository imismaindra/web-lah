@extends('layouts.admin')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')

@section('content')
    <div class="mx-auto max-w-2xl">
        {{-- Back --}}
        <a href="{{ route('admin.kategori.index') }}" class="mb-6 inline-flex items-center gap-1.5 text-sm font-medium text-stone-400 transition hover:text-stone-600 dark:hover:text-stone-300">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Kembali
        </a>

        {{-- Card --}}
        <div class="rounded-2xl border border-stone-200/60 bg-white p-6 dark:border-white/[0.06] dark:bg-[#171716] sm:p-8">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="font-serif text-xl font-bold tracking-tight">Edit Kategori</h2>
                    <p class="mt-1 text-sm text-stone-500 dark:text-stone-400">{{ $kategori->nama }}</p>
                </div>
                <code class="rounded-md bg-stone-100 px-2.5 py-1 text-xs text-stone-500 dark:bg-white/[0.05] dark:text-stone-400">{{ $kategori->slug }}</code>
            </div>

            <form method="POST" action="{{ route('admin.kategori.update', $kategori) }}" class="mt-8 space-y-6">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div>
                    <label for="nama" class="mb-1.5 block text-sm font-semibold text-stone-700 dark:text-stone-300">Nama Kategori</label>
                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        value="{{ old('nama', $kategori->nama) }}"
                        required
                        class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-2.5 text-sm text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10"
                    >
                    @error('nama')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="deskripsi" class="mb-1.5 block text-sm font-semibold text-stone-700 dark:text-stone-300">Deskripsi <span class="font-normal text-stone-400 dark:text-stone-500">(opsional)</span></label>
                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        rows="3"
                        placeholder="Deskripsi singkat tentang kategori ini"
                        class="w-full resize-none rounded-xl border border-stone-200 bg-stone-50 px-4 py-2.5 text-sm text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10"
                    >{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Info --}}
                <div class="flex items-center gap-4 rounded-xl bg-stone-50 px-4 py-3 text-xs text-stone-400 dark:bg-white/[0.02] dark:text-stone-500">
                    <span>Dibuat: {{ $kategori->created_at->format('d M Y, H:i') }}</span>
                    <span class="text-stone-200 dark:text-white/10">|</span>
                    <span>Diupdate: {{ $kategori->updated_at->format('d M Y, H:i') }}</span>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-[#1e3a5f] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16304a] dark:bg-[#5b9bd5] dark:text-[#0f0f0e] dark:hover:bg-[#7ab3e0]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Perbarui
                    </button>
                    <a href="{{ route('admin.kategori.index') }}" class="rounded-xl border border-stone-200 px-5 py-2.5 text-sm font-medium text-stone-600 transition hover:border-stone-300 hover:text-stone-900 dark:border-white/10 dark:text-stone-400 dark:hover:border-white/20 dark:hover:text-white">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
