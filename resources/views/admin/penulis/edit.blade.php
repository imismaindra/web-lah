@extends('layouts.admin')

@section('title', 'Edit Penulis')
@section('page-title', 'Edit Penulis')

@section('content')
    @php
        $label = 'mb-1.5 block text-sm font-semibold text-stone-700 dark:text-stone-300';
        $hint = 'mt-1 text-xs text-stone-400 dark:text-stone-500';
        $error = 'mt-1.5 text-xs text-red-500';
        $input = 'w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-2.5 text-sm text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10';
        $card = 'rounded-2xl border border-stone-200/60 bg-white p-6 dark:border-white/[0.06] dark:bg-[#171716] sm:p-7';
        $aside = 'rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]';
        $sectionTitle = 'font-serif text-base font-bold text-stone-800 dark:text-stone-200';
    @endphp

    <div class="mx-auto max-w-5xl">
        {{-- Back --}}
        <a href="{{ route('admin.penulis.index') }}" class="mb-6 inline-flex items-center gap-1.5 text-sm font-medium text-stone-400 transition hover:text-stone-600 dark:hover:text-stone-300">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Kembali
        </a>

        {{-- Profile header --}}
        <div class="mb-6 flex flex-col gap-4 rounded-2xl border border-stone-200/60 bg-white p-6 dark:border-white/[0.06] dark:bg-[#171716] sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center overflow-hidden rounded-full bg-stone-100 font-serif text-lg font-bold text-stone-500 ring-1 ring-stone-200 dark:bg-white/[0.05] dark:text-stone-400 dark:ring-white/10">
                    @if ($penulis->avatar)
                        <img src="{{ asset('storage/' . $penulis->avatar) }}" alt="{{ $penulis->nama }}" class="h-14 w-14 rounded-full object-cover">
                    @else
                        {{ substr($penulis->nama, 0, 1) }}
                    @endif
                </div>
                <div>
                    <h2 class="font-serif text-lg font-bold tracking-tight">{{ $penulis->nama }}</h2>
                    <code class="mt-1 inline-block rounded-md bg-stone-100 px-2 py-0.5 text-xs text-stone-500 dark:bg-white/[0.05] dark:text-stone-400">{{ $penulis->slug }}</code>
                </div>
            </div>
            <div class="flex flex-col gap-1 text-xs text-stone-400 dark:text-stone-500 sm:items-end">
                <span>Dibuat: {{ $penulis->created_at->format('d M Y, H:i') }}</span>
                <span>Diupdate: {{ $penulis->updated_at->format('d M Y, H:i') }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.penulis.update', $penulis) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="flex flex-col gap-6 lg:flex-row">
                {{-- Main Column --}}
                <div class="flex-1 space-y-6">
                    <section class="{{ $card }}">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-[#1e3a5f]/10 text-[#1e3a5f] dark:bg-[#5b9bd5]/10 dark:text-[#5b9bd5]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <div>
                                <h3 class="{{ $sectionTitle }}">Profil Publik</h3>
                                <p class="mt-0.5 text-xs text-stone-400 dark:text-stone-500">Nama, bio, dan tautan yang tampil di halaman penulis</p>
                            </div>
                        </div>

                        <div class="mt-6 space-y-5">
                            <div>
                                <label for="nama" class="{{ $label }}">Nama Penulis</label>
                                <input
                                    type="text"
                                    id="nama"
                                    name="nama"
                                    value="{{ old('nama', $penulis->nama) }}"
                                    required
                                    placeholder="Nama lengkap untuk tampilan publik"
                                    class="{{ $input }}"
                                >
                                @error('nama')
                                    <p class="{{ $error }}">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="bio" class="{{ $label }}">Bio <span class="font-normal text-stone-400 dark:text-stone-500">(opsional)</span></label>
                                <textarea
                                    id="bio"
                                    name="bio"
                                    rows="4"
                                    placeholder="Tentang penulis ini..."
                                    class="{{ $input }} resize-none"
                                >{{ old('bio', $penulis->bio) }}</textarea>
                                @error('bio')
                                    <p class="{{ $error }}">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="website" class="{{ $label }}">Website <span class="font-normal text-stone-400 dark:text-stone-500">(opsional)</span></label>
                                <input
                                    type="url"
                                    id="website"
                                    name="website"
                                    value="{{ old('website', $penulis->website) }}"
                                    placeholder="https://example.com"
                                    class="{{ $input }}"
                                >
                                @error('website')
                                    <p class="{{ $error }}">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </section>
                </div>

                {{-- Sidebar --}}
                <div class="w-full space-y-6 lg:w-80">
                    {{-- Akun --}}
                    <section class="{{ $aside }}">
                        <h3 class="{{ $sectionTitle }}">Akun User</h3>
                        <p class="mt-0.5 text-xs text-stone-400 dark:text-stone-500">Pemilik akun penulis ini</p>
                        <select id="user_id" name="user_id" required class="{{ $input }} mt-4">
                            <option value="">Pilih user</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $penulis->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="{{ $error }}">{{ $message }}</p>
                        @enderror
                    </section>

                    {{-- Avatar --}}
                    <section class="{{ $aside }}">
                        <h3 class="{{ $sectionTitle }}">Foto Profil</h3>
                        <p class="mt-0.5 text-xs text-stone-400 dark:text-stone-500">Opsional &middot; tampil sebagai avatar penulis</p>

                        <label for="avatar" class="group mt-5 flex cursor-pointer flex-col items-center gap-3 rounded-xl border-2 border-dashed border-stone-200 bg-stone-50 px-4 py-6 text-center transition hover:border-[#1e3a5f]/40 hover:bg-[#1e3a5f]/5 dark:border-white/10 dark:bg-white/[0.02] dark:hover:border-[#5b9bd5]/40 dark:hover:bg-[#5b9bd5]/5">
                            <div id="avatar-preview" class="flex h-20 w-20 items-center justify-center overflow-hidden rounded-full bg-white ring-1 ring-stone-200 dark:bg-white/[0.05] dark:ring-white/10">
                                @if ($penulis->avatar)
                                    <img src="{{ asset('storage/' . $penulis->avatar) }}" alt="{{ $penulis->nama }}" class="h-20 w-20 rounded-full object-cover">
                                @else
                                    <svg class="h-8 w-8 text-stone-300 dark:text-stone-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                @endif
                            </div>
                            <span class="flex items-center gap-1.5 text-xs font-semibold text-stone-600 transition group-hover:text-[#1e3a5f] dark:text-stone-300 dark:group-hover:text-[#5b9bd5]">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                                Ganti foto
                            </span>
                            <span class="text-[10px] text-stone-400 dark:text-stone-500">JPEG, PNG, WebP &middot; Maks 2MB</span>
                        </label>
                        <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden">
                        @error('avatar')
                            <p class="{{ $error }}">{{ $message }}</p>
                        @enderror
                    </section>

                    {{-- Actions --}}
                    <div class="{{ $aside }} space-y-3">
                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#1e3a5f] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16304a] active:scale-[0.98] dark:bg-[#5b9bd5] dark:text-[#0f0f0e] dark:hover:bg-[#7ab3e0]">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Perbarui Penulis
                        </button>
                        <a href="{{ route('admin.penulis.index') }}" class="flex w-full items-center justify-center rounded-xl border border-stone-200 px-5 py-2.5 text-sm font-medium text-stone-600 transition hover:border-stone-300 hover:text-stone-900 active:scale-[0.98] dark:border-white/10 dark:text-stone-400 dark:hover:border-white/20 dark:hover:text-white">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('avatar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    document.getElementById('avatar-preview').innerHTML = '<img src="' + ev.target.result + '" class="h-20 w-20 rounded-full object-cover">';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush