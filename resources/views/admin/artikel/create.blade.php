@extends('layouts.admin')

@section('title', 'Tulis Artikel')
@section('page-title', 'Tulis Artikel')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/trix@2.1.3/dist/trix.min.css">
    <style>
        .trix-toolbar {
            border-color: rgb(231 229 228 / 0.6) !important;
            border-radius: 0.75rem 0.75rem 0 0 !important;
            background: rgb(245 245 244) !important;
            padding: 0.5rem !important;
        }
        :is(.dark) .trix-toolbar {
            border-color: rgba(255, 255, 255, 0.06) !important;
            background: rgba(255, 255, 255, 0.02) !important;
        }
        .trix-toolbar .trix-button-group {
            border-color: rgb(231 229 228 / 0.6) !important;
        }
        :is(.dark) .trix-toolbar .trix-button-group {
            border-color: rgba(255, 255, 255, 0.06) !important;
        }
        .trix-toolbar .trix-button {
            color: rgb(120 113 108) !important;
        }
        .trix-toolbar .trix-button:hover {
            background: rgb(214 211 209 / 0.5) !important;
            color: rgb(68 64 60) !important;
        }
        :is(.dark) .trix-toolbar .trix-button {
            color: rgb(168 162 158) !important;
        }
        :is(.dark) .trix-toolbar .trix-button:hover {
            background: rgba(255, 255, 255, 0.05) !important;
            color: rgb(229 229 227) !important;
        }
        .trix-toolbar .trix-button.trix-active {
            background: rgb(30 58 95 / 0.1) !important;
            color: rgb(30 58 95) !important;
        }
        :is(.dark) .trix-toolbar .trix-button.trix-active {
            background: rgb(91 155 213 / 0.1) !important;
            color: rgb(91 155 213) !important;
        }
        .trix-content {
            border-color: rgb(231 229 228 / 0.6) !important;
            border-radius: 0 0 0.75rem 0.75rem !important;
            background: rgb(250 250 249) !important;
            color: rgb(41 37 36) !important;
            font-size: 0.875rem !important;
            line-height: 1.7 !important;
            padding: 1rem 1.25rem !important;
            min-height: 320px !important;
        }
        :is(.dark) .trix-content {
            border-color: rgba(255, 255, 255, 0.06) !important;
            background: rgba(255, 255, 255, 0.03) !important;
            color: rgb(229 229 227) !important;
        }
        .trix-content:focus {
            outline: none;
            border-color: rgb(30 58 95 / 0.4) !important;
            box-shadow: 0 0 0 3px rgb(30 58 95 / 0.08) !important;
        }
        :is(.dark) .trix-content:focus {
            border-color: rgb(91 155 213 / 0.4) !important;
            box-shadow: 0 0 0 3px rgb(91 155 213 / 0.08) !important;
        }
        .trix-content h1 { font-size: 1.5rem !important; font-weight: 700 !important; margin-bottom: 0.5rem !important; }
        .trix-content h2 { font-size: 1.25rem !important; font-weight: 700 !important; margin-bottom: 0.5rem !important; }
        .trix-content h3 { font-size: 1.125rem !important; font-weight: 600 !important; margin-bottom: 0.5rem !important; }
        .trix-content blockquote {
            border-left: 3px solid rgb(30 58 95 / 0.3) !important;
            padding-left: 1rem !important;
            color: rgb(120 113 108) !important;
            font-style: italic !important;
        }
        :is(.dark) .trix-content blockquote {
            border-left-color: rgb(91 155 213 / 0.3) !important;
            color: rgb(168 162 158) !important;
        }
        .trix-content img {
            border-radius: 0.75rem !important;
            max-width: 100% !important;
        }
        .trix-placeholder {
            color: rgb(168 162 158) !important;
        }
        .trix-dialog {
            background: white !important;
            border: 1px solid rgb(231 229 228 / 0.6) !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 25px rgb(0 0 0 / 0.1) !important;
        }
        :is(.dark) .trix-dialog {
            background: #171716 !important;
            border-color: rgba(255, 255, 255, 0.06) !important;
        }
    </style>
@endpush

@section('content')
    <div class="mx-auto max-w-4xl">
        {{-- Back --}}
        <a href="{{ route('admin.artikel.index') }}" class="mb-6 inline-flex items-center gap-1.5 text-sm font-medium text-stone-400 transition hover:text-stone-600 dark:hover:text-stone-300">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Kembali
        </a>

        <form method="POST" action="{{ route('admin.artikel.store') }}" enctype="multipart/form-data" id="artikel-form">
            @csrf

            <div class="flex flex-col gap-6 lg:flex-row">
                {{-- Main Content --}}
                <div class="flex-1 space-y-6">
                    {{-- Judul --}}
                    <div class="rounded-2xl border border-stone-200/60 bg-white p-6 dark:border-white/[0.06] dark:bg-[#171716]">
                        <label for="judul" class="mb-1.5 block text-sm font-semibold text-stone-700 dark:text-stone-300">Judul Artikel</label>
                        <input
                            type="text"
                            id="judul"
                            name="judul"
                            value="{{ old('judul') }}"
                            required
                            autofocus
                            placeholder="Judul yang menarik dan deskriptif"
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-lg font-serif font-bold text-stone-800 outline-none transition placeholder:text-stone-300 focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-200 dark:placeholder:text-stone-600 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10"
                        >
                        @error('judul')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konten (Trix) --}}
                    <div class="rounded-2xl border border-stone-200/60 bg-white p-6 dark:border-white/[0.06] dark:bg-[#171716]">
                        <label class="mb-1.5 block text-sm font-semibold text-stone-700 dark:text-stone-300">Konten Artikel</label>
                        <input id="konten" type="hidden" name="konten" value="{{ old('konten') }}">
                        <trix-editor input="konten" placeholder="Mulai menulis artikel sejarah..."></trix-editor>
                        @error('konten')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="w-full space-y-6 lg:w-80">
                    {{-- Publish --}}
                    <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
                        <h3 class="mb-4 text-sm font-bold text-stone-800 dark:text-stone-200">Publikasi</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="status" class="mb-1.5 block text-xs font-semibold text-stone-500 dark:text-stone-400">Status</label>
                                <select id="status" name="status" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-sm text-stone-700 outline-none transition focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-300 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10">
                                    <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full rounded-xl bg-[#1e3a5f] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16304a] dark:bg-[#5b9bd5] dark:text-[#0f0f0e] dark:hover:bg-[#7ab3e0]">
                                Simpan Artikel
                            </button>
                        </div>
                    </div>

                    {{-- Kategori --}}
                    <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
                        <h3 class="mb-4 text-sm font-bold text-stone-800 dark:text-stone-200">Kategori</h3>
                        <select id="kategori_id" name="kategori_id" required class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-sm text-stone-700 outline-none transition focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-300 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10">
                            <option value="">Pilih kategori</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Era --}}
                    <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
                        <h3 class="mb-4 text-sm font-bold text-stone-800 dark:text-stone-200">Era (Zaman)</h3>
                        <select id="era_id" name="era_id" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-sm text-stone-700 outline-none transition focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-300 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10">
                            <option value="">Pilih era</option>
                            @foreach ($eras as $era)
                                <option value="{{ $era->id }}" {{ old('era_id') == $era->id ? 'selected' : '' }}>{{ $era->nama }} ({{ $era->periode }})</option>
                            @endforeach
                        </select>
                        @error('era_id')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Topik --}}
                    <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
                        <h3 class="mb-4 text-sm font-bold text-stone-800 dark:text-stone-200">Topik</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($topiks as $topik)
                                <label class="flex cursor-pointer items-center gap-1.5 rounded-lg border border-stone-200 bg-stone-50 px-2.5 py-1.5 text-xs text-stone-600 transition hover:border-stone-300 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-300 dark:hover:border-white/20">
                                    <input type="checkbox" name="topik_ids[]" value="{{ $topik->id }}" class="h-3.5 w-3.5 accent-[#1e3a5f] dark:accent-[#5b9bd5]" {{ in_array($topik->id, old('topik_ids', [])) ? 'checked' : '' }}>
                                    {{ $topik->nama }}
                                </label>
                            @endforeach
                        </div>
                        @error('topik_ids')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Gambar Sampul --}}
                    <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
                        <h3 class="mb-4 text-sm font-bold text-stone-800 dark:text-stone-200">Gambar Sampul</h3>
                        <div id="dropzone" class="relative cursor-pointer rounded-xl border-2 border-dashed border-stone-200 bg-stone-50 p-6 text-center transition hover:border-[#1e3a5f]/40 hover:bg-[#1e3a5f]/5 dark:border-white/10 dark:bg-white/[0.02] dark:hover:border-[#5b9bd5]/40 dark:hover:bg-[#5b9bd5]/5">
                            <input type="file" id="gambar" name="gambar" accept="image/*" class="absolute inset-0 cursor-pointer opacity-0">
                            <svg class="mx-auto h-8 w-8 text-stone-300 dark:text-stone-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                            <p class="mt-2 text-xs text-stone-400 dark:text-stone-500">Klik atau seret gambar ke sini</p>
                            <p class="mt-1 text-[10px] text-stone-300 dark:text-stone-600">JPEG, PNG, WebP &middot; Maks 2MB</p>
                        </div>
                        <div id="preview" class="mt-3 hidden">
                            <img id="preview-img" class="w-full rounded-xl object-cover" alt="Preview">
                        </div>
                        @error('gambar')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Ringkasan --}}
                    <div class="rounded-2xl border border-stone-200/60 bg-white p-5 dark:border-white/[0.06] dark:bg-[#171716]">
                        <h3 class="mb-4 text-sm font-bold text-stone-800 dark:text-stone-200">Ringkasan</h3>
                        <textarea
                            id="ringkasan"
                            name="ringkasan"
                            rows="4"
                            maxlength="1000"
                            placeholder="Ringkasan singkat artikel (untuk SEO & preview)"
                            class="w-full resize-none rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-sm text-stone-700 outline-none transition placeholder:text-stone-400 focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-300 dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10"
                        >{{ old('ringkasan') }}</textarea>
                        <p class="mt-1.5 text-right text-[10px] text-stone-300 dark:text-stone-600"><span id="ringkasan-count">0</span>/1000</p>
                        @error('ringkasan')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/trix@2.1.3/dist/trix.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Trix image upload handler
            document.addEventListener('trix-attachment-add', function(event) {
                const attachment = event.attachment;
                if (attachment.file) {
                    uploadTrixFile(attachment);
                }
            });

            function uploadTrixFile(attachment) {
                const file = attachment.file;
                const form = new FormData();
                form.append('file', file);
                form.append('_token', '{{ csrf_token() }}');

                attachment.setUploadProgress(0);

                fetch('{{ route("admin.upload.store") }}', {
                    method: 'POST',
                    body: form,
                })
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    attachment.setAttributes({
                        href: data.url,
                        url: data.url,
                    });
                })
                .catch(function() {
                    attachment.remove();
                });
            }

            // Image preview
            const gambarInput = document.getElementById('gambar');
            const preview = document.getElementById('preview');
            const previewImg = document.getElementById('preview-img');

            gambarInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        previewImg.src = ev.target.result;
                        preview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Ringkasan character count
            const ringkasan = document.getElementById('ringkasan');
            const counter = document.getElementById('ringkasan-count');

            function updateCount() {
                counter.textContent = ringkasan.value.length;
            }
            ringkasan.addEventListener('input', updateCount);
            updateCount();
        });
    </script>
@endpush
