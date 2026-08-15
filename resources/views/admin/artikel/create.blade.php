@extends('layouts.admin')

@section('title', 'Tulis Artikel')
@section('page-title', 'Tulis Artikel')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/trix@2.1.3/dist/trix.min.css">
    <style>
        /* Trix - Dark Theme */
        @media (prefers-color-scheme: dark) {
            .trix-button--icon {
                -webkit-filter: invert(100%) !important;
                filter: invert(100%) !important;
            }
            .trix-toolbar .trix-button {
                background: #ffffff !important;
            }
            trix-editor {
                background-color: #1a202c !important;
                color: #e2e8f0 !important;
            }
        }

        /* Trix Content Area */
        .trix-content {
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-top: none !important;
            border-radius: 0 0 0.75rem 0.75rem !important;
            background: rgba(255, 255, 255, 0.02) !important;
            color: rgb(229 229 227) !important;
            font-size: 0.9375rem !important;
            line-height: 1.75 !important;
            padding: 1.25rem 1.5rem !important;
            min-height: 400px !important;
        }
        .trix-content:focus {
            outline: none;
            border-color: rgb(91 155 213 / 0.5) !important;
            box-shadow: 0 0 0 3px rgb(91 155 213 / 0.1) !important;
        }
        .trix-content h1 { font-size: 1.5rem !important; font-weight: 700 !important; margin-bottom: 0.5rem !important; }
        .trix-content h2 { font-size: 1.25rem !important; font-weight: 700 !important; margin-bottom: 0.5rem !important; }
        .trix-content h3 { font-size: 1.125rem !important; font-weight: 600 !important; margin-bottom: 0.5rem !important; }
        .trix-content blockquote {
            border-left: 3px solid rgb(91 155 213 / 0.3) !important;
            padding-left: 1rem !important;
            color: rgb(168 162 158) !important;
            font-style: italic !important;
        }
        .trix-content img {
            border-radius: 0.75rem !important;
            max-width: 100% !important;
        }
        .trix-placeholder {
            color: rgb(128 128 128) !important;
        }

        /* Trix Dialog */
        .trix-dialog {
            background: #1e1e1d !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 25px rgb(0 0 0 / 0.3) !important;
        }
        .trix-dialog__link-fields {
            padding: 0.75rem !important;
        }
        .trix-input {
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 0.5rem !important;
            padding: 0.5rem 0.75rem !important;
            font-size: 0.875rem !important;
            background: rgba(255, 255, 255, 0.04) !important;
            color: rgb(229 229 227) !important;
        }
        .trix-input:focus {
            border-color: rgb(91 155 213 / 0.5) !important;
            outline: none !important;
        }
        .trix-dialog__button {
            border-radius: 0.5rem !important;
            font-size: 0.875rem !important;
            padding: 0.5rem 1rem !important;
        }
        .trix-dialog__button--primary {
            background: rgb(91 155 213) !important;
            color: rgb(15 15 14) !important;
            border: none !important;
        }

        /* Custom select styling */
        .custom-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }
    </style>
@endpush

@section('content')
    <form method="POST" action="{{ route('admin.artikel.store') }}" enctype="multipart/form-data" id="artikel-form">
        @csrf

        <div class="flex flex-col gap-8 lg:flex-row">
            {{-- Main Writing Area --}}
            <div class="flex-1 min-w-0">
                {{-- Back link --}}
                <a href="{{ route('admin.artikel.index') }}" class="mb-6 inline-flex items-center gap-1.5 text-sm font-medium text-stone-400 transition hover:text-stone-600 dark:hover:text-stone-300">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    Kembali
                </a>

                {{-- Title --}}
                <div class="mb-4">
                    <input
                        type="text"
                        id="judul"
                        name="judul"
                        value="{{ old('judul') }}"
                        required
                        autofocus
                        placeholder="Judul artikel"
                        class="w-full border-none bg-transparent text-2xl font-bold tracking-tight text-stone-800 outline-none placeholder:text-stone-300 focus:ring-0 dark:text-stone-100 dark:placeholder:text-stone-600 md:text-3xl"
                    >
                    @error('judul')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Divider --}}
                <div class="mb-6 h-px bg-stone-200/60 dark:bg-white/[0.06]"></div>

                {{-- Content Editor --}}
                <div>
                    <input id="konten" type="hidden" name="konten" value="{{ old('konten') }}">
                    <trix-editor input="konten" placeholder="Mulai menulis artikel sejarah..."></trix-editor>
                    @error('konten')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="w-full space-y-6 lg:w-72 xl:w-80">
                {{-- Publish Actions --}}
                <div class="space-y-3">
                    <button type="submit" class="w-full rounded-xl bg-[#1e3a5f] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#16304a] hover:shadow dark:bg-[#5b9bd5] dark:text-[#0f0f0e] dark:hover:bg-[#7ab3e0]">
                        Simpan Artikel
                    </button>
                    <div>
                        <label for="status" class="mb-1.5 block text-xs font-medium text-stone-500 dark:text-stone-400">Status</label>
                        <select id="status" name="status" class="custom-select w-full rounded-lg border border-stone-200 bg-white px-3 py-2 text-sm text-stone-600 outline-none transition focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-300 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10">
                            <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="h-px bg-stone-200/60 dark:bg-white/[0.06]"></div>

                {{-- Metadata --}}
                <div class="space-y-4">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-stone-400 dark:text-stone-500">Metadata</h3>

                    {{-- Kategori --}}
                    <div>
                        <label for="kategori_id" class="mb-1.5 block text-xs font-medium text-stone-500 dark:text-stone-400">Kategori</label>
                        <select id="kategori_id" name="kategori_id" required class="custom-select w-full rounded-lg border border-stone-200 bg-white px-3 py-2 text-sm text-stone-600 outline-none transition focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-300 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10">
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
                    <div>
                        <label for="era_id" class="mb-1.5 block text-xs font-medium text-stone-500 dark:text-stone-400">Era (Zaman)</label>
                        <select id="era_id" name="era_id" class="custom-select w-full rounded-lg border border-stone-200 bg-white px-3 py-2 text-sm text-stone-600 outline-none transition focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-300 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10">
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
                    <div>
                        <label class="mb-2 block text-xs font-medium text-stone-500 dark:text-stone-400">Topik</label>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($topiks as $topik)
                                <label class="inline-flex cursor-pointer items-center gap-1.5 rounded-md border border-stone-200 bg-white px-2 py-1 text-xs text-stone-600 transition hover:border-stone-300 hover:bg-stone-50 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-300 dark:hover:border-white/20">
                                    <input type="checkbox" name="topik_ids[]" value="{{ $topik->id }}" class="h-3 w-3 rounded accent-[#1e3a5f] dark:accent-[#5b9bd5]" {{ in_array($topik->id, old('topik_ids', [])) ? 'checked' : '' }}>
                                    {{ $topik->nama }}
                                </label>
                            @endforeach
                        </div>
                        @error('topik_ids')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Divider --}}
                <div class="h-px bg-stone-200/60 dark:bg-white/[0.06]"></div>

                {{-- Ringkasan --}}
                <div>
                    <label for="ringkasan" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-stone-400 dark:text-stone-500">Ringkasan</label>
                    <textarea
                        id="ringkasan"
                        name="ringkasan"
                        rows="3"
                        maxlength="1000"
                        placeholder="Ringkasan singkat untuk SEO & preview"
                        class="w-full resize-none rounded-lg border border-stone-200 bg-white px-3 py-2 text-sm text-stone-600 outline-none transition placeholder:text-stone-400 focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-300 dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]/10"
                    >{{ old('ringkasan') }}</textarea>
                    <p class="mt-1 text-right text-[10px] text-stone-300 dark:text-stone-600"><span id="ringkasan-count">0</span>/1000</p>
                    @error('ringkasan')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Divider --}}
                <div class="h-px bg-stone-200/60 dark:bg-white/[0.06]"></div>

                {{-- Gambar Sampul --}}
                <div>
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-stone-400 dark:text-stone-500">Gambar Sampul</label>
                    <div id="dropzone" class="relative cursor-pointer rounded-lg border-2 border-dashed border-stone-200 bg-stone-50/50 p-4 text-center transition hover:border-[#1e3a5f]/40 hover:bg-[#1e3a5f]/5 dark:border-white/10 dark:bg-white/[0.02] dark:hover:border-[#5b9bd5]/40 dark:hover:bg-[#5b9bd5]/5">
                        <input type="file" id="gambar" name="gambar" accept="image/*" class="absolute inset-0 cursor-pointer opacity-0">
                        <svg class="mx-auto h-6 w-6 text-stone-300 dark:text-stone-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                        <p class="mt-2 text-xs text-stone-400 dark:text-stone-500">Klik atau seret gambar</p>
                        <p class="mt-0.5 text-[10px] text-stone-300 dark:text-stone-600">JPEG, PNG, WebP &middot; Maks 2MB</p>
                    </div>
                    <div id="preview" class="mt-3 hidden">
                        <img id="preview-img" class="w-full rounded-lg object-cover" alt="Preview">
                    </div>
                    @error('gambar')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </form>
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
