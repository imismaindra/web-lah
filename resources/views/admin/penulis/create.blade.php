@extends('layouts.admin')

@section('title', 'Tambah Penulis')
@section('page-title', 'Tambah Penulis')

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

        <form method="POST" action="{{ route('admin.penulis.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="flex flex-col gap-6 lg:flex-row">
                {{-- Main Column --}}
                <div class="flex-1 space-y-6">
                    {{-- Profil Publik --}}
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
                                    value="{{ old('nama') }}"
                                    required
                                    autofocus
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
                                >{{ old('bio') }}</textarea>
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
                                    value="{{ old('website') }}"
                                    placeholder="https://example.com"
                                    class="{{ $input }}"
                                >
                                @error('website')
                                    <p class="{{ $error }}">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </section>

                    {{-- Akun Login --}}
                    <section class="{{ $card }}">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-[#1e3a5f]/10 text-[#1e3a5f] dark:bg-[#5b9bd5]/10 dark:text-[#5b9bd5]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
                            </div>
                            <div>
                                <h3 class="{{ $sectionTitle }}">Akun Login</h3>
                                <p class="mt-0.5 text-xs text-stone-400 dark:text-stone-500">Akun yang mewakili penulis ini di panel</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            {{-- Mode --}}
                            <div class="grid grid-cols-2 gap-1 rounded-xl bg-stone-100 p-1 dark:bg-white/[0.03]">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="mode" value="existing" checked class="peer sr-only">
                                    <span class="flex items-center justify-center rounded-lg px-3 py-2 text-sm font-medium text-stone-500 transition peer-checked:bg-white peer-checked:text-[#1e3a5f] peer-checked:shadow-sm dark:text-stone-400 dark:peer-checked:bg-white/[0.08] dark:peer-checked:text-[#5b9bd5]">Pilih User</span>
                                </label>
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="mode" value="new" class="peer sr-only">
                                    <span class="flex items-center justify-center rounded-lg px-3 py-2 text-sm font-medium text-stone-500 transition peer-checked:bg-white peer-checked:text-[#1e3a5f] peer-checked:shadow-sm dark:text-stone-400 dark:peer-checked:bg-white/[0.08] dark:peer-checked:text-[#5b9bd5]">Buat Akun Baru</span>
                                </label>
                            </div>

                            {{-- Existing --}}
                            <div id="existing-user-field" class="mt-5">
                                <label for="user_id" class="{{ $label }}">Akun User</label>
                                <select id="user_id" name="user_id" class="{{ $input }}">
                                    <option value="">Pilih user</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <p class="{{ $error }}">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- New User --}}
                            <div id="new-user-fields" class="mt-5 hidden">
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="name" class="{{ $label }}">Nama Lengkap</label>
                                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Nama lengkap user" class="{{ $input }}">
                                        @error('name')
                                            <p class="{{ $error }}">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="email" class="{{ $label }}">Email</label>
                                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" class="{{ $input }}">
                                        @error('email')
                                            <p class="{{ $error }}">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="password" class="{{ $label }}">Password</label>
                                        <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" class="{{ $input }}">
                                        @error('password')
                                            <p class="{{ $error }}">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="password_confirmation" class="{{ $label }}">Konfirmasi Password</label>
                                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" class="{{ $input }}">
                                        @error('password_confirmation')
                                            <p class="{{ $error }}">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                {{-- Sidebar --}}
                <div class="w-full space-y-6 lg:w-80">
                    {{-- Avatar --}}
                    <section class="{{ $aside }}">
                        <h3 class="{{ $sectionTitle }}">Foto Profil</h3>
                        <p class="mt-0.5 text-xs text-stone-400 dark:text-stone-500">Opsional &middot; tampil sebagai avatar penulis</p>

                        <label for="avatar" class="group mt-5 flex cursor-pointer flex-col items-center gap-3 rounded-xl border-2 border-dashed border-stone-200 bg-stone-50 px-4 py-6 text-center transition hover:border-[#1e3a5f]/40 hover:bg-[#1e3a5f]/5 dark:border-white/10 dark:bg-white/[0.02] dark:hover:border-[#5b9bd5]/40 dark:hover:bg-[#5b9bd5]/5">
                            <div id="avatar-preview" class="flex h-20 w-20 items-center justify-center overflow-hidden rounded-full bg-white ring-1 ring-stone-200 dark:bg-white/[0.05] dark:ring-white/10">
                                <svg class="h-8 w-8 text-stone-300 dark:text-stone-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <span class="flex items-center gap-1.5 text-xs font-semibold text-stone-600 transition group-hover:text-[#1e3a5f] dark:text-stone-300 dark:group-hover:text-[#5b9bd5]">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                                Pilih foto
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
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                            Simpan Penulis
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

        const modeInputs = document.querySelectorAll('input[name="mode"]');
        const existingField = document.getElementById('existing-user-field');
        const newFields = document.getElementById('new-user-fields');
        const newUserInputs = newFields.querySelectorAll('input');

        function toggleMode() {
            const isExisting = document.querySelector('input[name="mode"]:checked').value === 'existing';
            existingField.classList.toggle('hidden', !isExisting);
            newFields.classList.toggle('hidden', isExisting);
            document.getElementById('user_id').disabled = !isExisting;
            newUserInputs.forEach(function(input) {
                input.required = !isExisting;
                input.disabled = isExisting;
            });
        }

        modeInputs.forEach(function(radio) {
            radio.addEventListener('change', toggleMode);
        });
        toggleMode();
    </script>
@endpush