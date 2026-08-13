@extends('layouts.admin')

@section('title', 'Edit Era')
@section('page-title', 'Edit Era')

@section('content')
    <div class="mx-auto max-w-2xl">
        {{-- Back --}}
        <a href="{{ route('admin.era.index') }}" class="mb-6 inline-flex items-center gap-1.5 text-sm font-medium text-stone-400 transition hover:text-stone-600 dark:hover:text-stone-300">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Kembali
        </a>

        {{-- Card --}}
        <div class="rounded-2xl border border-stone-200/60 bg-white p-6 dark:border-white/[0.06] dark:bg-[#171716] sm:p-8">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="font-serif text-xl font-bold tracking-tight">Edit Era</h2>
                    <p class="mt-1 text-sm text-stone-500 dark:text-stone-400">{{ $era->nama }}</p>
                </div>
                <code class="rounded-md bg-stone-100 px-2.5 py-1 text-xs text-stone-500 dark:bg-white/[0.05] dark:text-stone-400">{{ $era->slug }}</code>
            </div>

            <form method="POST" action="{{ route('admin.era.update', $era) }}" enctype="multipart/form-data" class="mt-8 space-y-6">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div>
                    <label for="nama" class="mb-1.5 block text-sm font-semibold text-stone-700 dark:text-stone-300">Nama Era</label>
                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        value="{{ old('nama', $era->nama) }}"
                        required
                        class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-2.5 text-sm text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10"
                    >
                    @error('nama')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-6 sm:grid-cols-2">
                    {{-- Periode --}}
                    <div>
                        <label for="periode" class="mb-1.5 block text-sm font-semibold text-stone-700 dark:text-stone-300">Periode <span class="font-normal text-stone-400 dark:text-stone-500">(opsional)</span></label>
                        <input
                            type="text"
                            id="periode"
                            name="periode"
                            value="{{ old('periode', $era->periode) }}"
                            placeholder="Contoh: 3000 SM – 500 M"
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-2.5 text-sm text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10"
                        >
                        @error('periode')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Urutan --}}
                    <div>
                        <label for="urutan" class="mb-1.5 block text-sm font-semibold text-stone-700 dark:text-stone-300">Urutan <span class="font-normal text-stone-400 dark:text-stone-500">(opsional)</span></label>
                        <input
                            type="number"
                            id="urutan"
                            name="urutan"
                            value="{{ old('urutan', $era->urutan) }}"
                            min="0"
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-2.5 text-sm text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10"
                        >
                        @error('urutan')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Gambar --}}
                <div>
                    <label for="gambar" class="mb-1.5 block text-sm font-semibold text-stone-700 dark:text-stone-300">Gambar <span class="font-normal text-stone-400 dark:text-stone-500">(opsional)</span></label>
                    <div class="flex items-center gap-4">
                        <div id="gambar-preview" class="flex h-20 w-32 flex-shrink-0 items-center justify-center overflow-hidden rounded-xl bg-stone-100 dark:bg-white/[0.05]">
                            @if ($era->gambar)
                                <img src="{{ asset('storage/' . $era->gambar) }}" alt="{{ $era->nama }}" class="h-20 w-32 rounded-xl object-cover">
                            @else
                                <svg class="h-6 w-6 text-stone-300 dark:text-stone-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                            @endif
                        </div>
                        <input type="file" id="gambar" name="gambar" accept="image/*" class="text-sm text-stone-500 file:mr-4 file:rounded-xl file:border-0 file:bg-stone-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-stone-700 hover:file:bg-stone-200 dark:file:bg-white/[0.05] dark:file:text-stone-400 dark:hover:file:bg-white/[0.08]">
                    </div>
                    @error('gambar')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-[#1e3a5f] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16304a] dark:bg-[#5b9bd5] dark:text-[#0f0f0e] dark:hover:bg-[#7ab3e0]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Perbarui
                    </button>
                    <a href="{{ route('admin.era.index') }}" class="rounded-xl border border-stone-200 px-5 py-2.5 text-sm font-medium text-stone-600 transition hover:border-stone-300 hover:text-stone-900 dark:border-white/10 dark:text-stone-400 dark:hover:border-white/20 dark:hover:text-white">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('gambar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    document.getElementById('gambar-preview').innerHTML = '<img src="' + ev.target.result + '" class="h-20 w-32 rounded-xl object-cover">';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush