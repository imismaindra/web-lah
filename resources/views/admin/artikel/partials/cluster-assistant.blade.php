@php
    $currentIsPillar = old('is_pillar', isset($artikel) ? ($artikel->is_pillar ? '1' : '0') : '0');
    $currentPillarId = old('pillar_id', isset($artikel) ? ($artikel->pillar_id ?? '') : '');
    $roleType = $currentIsPillar === '1' ? 'pilar' : (!empty($currentPillarId) ? 'pendukung' : 'standar');
@endphp

<div class="space-y-4 rounded-2xl border border-stone-200/80 bg-white p-4 shadow-2xs dark:border-white/[0.06] dark:bg-[#171716]">
    <div class="flex items-center justify-between border-b border-stone-100 pb-2.5 dark:border-white/[0.06]">
        <div class="flex items-center gap-2">
            <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-[#1e3a5f]/10 text-[#1e3a5f] dark:bg-amber-500/15 dark:text-amber-400">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
            </span>
            <h3 class="text-xs font-bold uppercase tracking-wider text-stone-700 dark:text-stone-300">Kluster Topik & Tautan</h3>
        </div>
        <a href="{{ route('admin.kluster.index') }}" target="_blank" class="text-[11px] font-medium text-[#1e3a5f] hover:underline dark:text-stone-300 dark:hover:text-white" title="Buka Peta Kluster">
            Lihat Peta &rarr;
        </a>
    </div>

    {{-- Role Selection (Standar, Pilar, Pendukung) --}}
    <div>
        <label class="mb-1.5 block text-xs font-medium text-stone-500 dark:text-stone-400">Peran Halaman</label>
        <div class="grid grid-cols-3 gap-1 rounded-xl bg-stone-100 p-1 dark:bg-white/[0.04]">
            <label class="cluster-role-option flex cursor-pointer items-center justify-center rounded-lg py-1.5 text-xs font-semibold transition text-stone-600 dark:text-stone-400 has-checked:bg-white has-checked:text-stone-900 has-checked:shadow-xs dark:has-checked:bg-[#20201e] dark:has-checked:text-white">
                <input type="radio" name="cluster_role" value="standar" class="sr-only" {{ $roleType === 'standar' ? 'checked' : '' }}>
                <span>Standar</span>
            </label>
            <label class="cluster-role-option flex cursor-pointer items-center justify-center rounded-lg py-1.5 text-xs font-semibold transition text-stone-600 dark:text-stone-400 has-checked:bg-[#1e3a5f] has-checked:text-white has-checked:shadow-xs dark:has-checked:bg-white dark:has-checked:text-stone-900">
                <input type="radio" name="cluster_role" value="pilar" class="sr-only" {{ $roleType === 'pilar' ? 'checked' : '' }}>
                <span>Pilar Hub</span>
            </label>
            <label class="cluster-role-option flex cursor-pointer items-center justify-center rounded-lg py-1.5 text-xs font-semibold transition text-stone-600 dark:text-stone-400 has-checked:bg-white has-checked:text-stone-900 has-checked:shadow-xs dark:has-checked:bg-[#20201e] dark:has-checked:text-white">
                <input type="radio" name="cluster_role" value="pendukung" class="sr-only" {{ $roleType === 'pendukung' ? 'checked' : '' }}>
                <span>Turunan</span>
            </label>
        </div>

        {{-- Hidden Form Inputs passed to Laravel --}}
        <input type="hidden" id="input_is_pillar" name="is_pillar" value="{{ $currentIsPillar }}">
    </div>

    {{-- Pillar Selector (Visible when Pendukung is active) --}}
    <div id="pillar-selector-container" class="{{ $roleType === 'pendukung' ? '' : 'hidden' }} space-y-2">
        <label for="pillar_id" class="block text-xs font-medium text-stone-600 dark:text-stone-300">
            Artikel Pilar Induk <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <select id="pillar_id" name="pillar_id" class="custom-select w-full rounded-lg border border-stone-200 bg-white px-3 py-2 text-xs text-stone-700 outline-none transition focus:border-[#1e3a5f] dark:border-white/10 dark:bg-[#121211] dark:text-stone-200">
                <option value="">-- Pilih Artikel Pilar --</option>
                @foreach ($pillars as $pil)
                    <option
                        value="{{ $pil->id }}"
                        data-slug="{{ $pil->slug }}"
                        data-title="{{ $pil->judul }}"
                        {{ (string)$currentPillarId === (string)$pil->id ? 'selected' : '' }}
                    >
                        {{ $pil->judul }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Live Pillar Backlink Status Box --}}
        <div id="pillar-backlink-status" class="rounded-xl border p-3 text-xs transition">
            {{-- Injected dynamically via JS --}}
        </div>
    </div>

    {{-- Internal Links Quick Helper Toolbar --}}
    <div class="border-t border-stone-100 pt-3 dark:border-white/[0.06] space-y-2">
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-stone-700 dark:text-stone-300">Asisten Internal Link</span>
            <span id="detected-links-count" class="rounded-full bg-stone-100 px-2 py-0.5 text-[10px] font-bold text-stone-600 dark:bg-white/[0.06] dark:text-stone-400">
                0 tautan
            </span>
        </div>

        <button
            type="button"
            id="btn-open-smart-link-modal"
            class="flex w-full items-center justify-center gap-2 rounded-xl border border-stone-200 bg-stone-50/80 px-3 py-2 text-xs font-semibold text-stone-700 shadow-2xs transition hover:bg-stone-100 hover:text-stone-900 active:scale-[0.98] dark:border-white/10 dark:bg-white/[0.04] dark:text-stone-200 dark:hover:bg-white/[0.08]"
        >
            <svg class="h-3.5 w-3.5 text-[#1e3a5f] dark:text-stone-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
            <span>Tautkan Artikel (Ctrl+K)</span>
        </button>

        {{-- Opportunities Container --}}
        <div id="link-opportunities-container" class="space-y-1.5 hidden pt-1">
            <div class="flex items-center justify-between text-[11px] font-medium text-stone-500">
                <span>Peluang Kata Kunci Ditemukan:</span>
            </div>
            <div id="opportunities-list" class="space-y-1"></div>
        </div>
    </div>
</div>
