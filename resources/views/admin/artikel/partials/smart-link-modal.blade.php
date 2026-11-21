{{-- Smart Internal Linking Modal --}}
<div id="smart-link-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-2xs transition-opacity opacity-0 pointer-events-none" aria-modal="true" role="dialog">
    <div class="relative w-full max-w-lg transform overflow-hidden rounded-2xl border border-stone-200/80 bg-white p-5 shadow-xl transition-all scale-95 dark:border-white/10 dark:bg-[#171716] sm:p-6" id="smart-link-modal-card">
        {{-- Modal Header --}}
        <div class="flex items-center justify-between border-b border-stone-100 pb-3 dark:border-white/[0.06]">
            <div class="flex items-center gap-2.5">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#1e3a5f]/10 text-[#1e3a5f] dark:bg-amber-500/15 dark:text-amber-400">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                </span>
                <h3 class="font-serif text-base font-bold text-stone-900 dark:text-stone-100">Sisipkan Tautan Internal</h3>
            </div>
            <button type="button" id="btn-close-smart-link-modal" class="rounded-lg p-1.5 text-stone-400 hover:bg-stone-100 hover:text-stone-700 dark:hover:bg-white/10 dark:hover:text-stone-200" title="Tutup (Esc)">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>

        {{-- Selection status strip --}}
        <div id="smart-link-selected-text-box" class="mt-3 flex items-center justify-between rounded-xl bg-stone-50 px-3 py-2 text-xs text-stone-600 dark:bg-white/[0.03] dark:text-stone-300">
            <span class="text-stone-400">Teks jangkar (anchor):</span>
            <span id="smart-link-selected-text" class="font-medium font-serif italic text-stone-800 dark:text-stone-200 line-clamp-1 max-w-[240px]">
                (Belum memilih teks, judul artikel akan disisipkan)
            </span>
        </div>

        {{-- Search Input --}}
        <div class="relative mt-3">
            <input
                type="text"
                id="smart-link-search-input"
                placeholder="Cari artikel rujukan berdasarkan judul atau topik..."
                autocomplete="off"
                class="w-full rounded-xl border border-stone-200 bg-white py-2.5 pl-9 pr-4 text-xs text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-[#1e3a5f] focus:ring-1 focus:ring-[#1e3a5f]/20 dark:border-white/10 dark:bg-[#121211] dark:text-stone-200 dark:placeholder:text-stone-500 dark:focus:border-stone-400 dark:focus:bg-[#121211]"
            >
            <svg class="pointer-events-none absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        </div>

        {{-- Quick Pillar Pin (If this article belongs to a pillar) --}}
        <div id="smart-link-pillar-quick-pin" class="mt-3 hidden">
            <div class="flex items-center justify-between rounded-xl border border-amber-200/80 bg-amber-50/70 p-3 text-xs dark:border-amber-500/20 dark:bg-amber-950/25">
                <div class="min-w-0 pr-2">
                    <span class="font-bold text-[10px] uppercase tracking-wider text-amber-800 dark:text-amber-300">Pilar Induk Anda:</span>
                    <p id="smart-link-pillar-title" class="font-serif font-bold text-stone-900 dark:text-stone-100 line-clamp-1 mt-0.5">-</p>
                </div>
                <button
                    type="button"
                    id="btn-smart-link-pillar-insert"
                    class="flex-shrink-0 inline-flex items-center gap-1.5 rounded-lg bg-[#1e3a5f] px-3 py-1.5 text-xs font-semibold text-white shadow-2xs transition hover:bg-[#16304a] dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200"
                >
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                    <span>Tautkan ke Pilar</span>
                </button>
            </div>
        </div>

        {{-- Results List --}}
        <div class="mt-3 max-h-60 overflow-y-auto space-y-1 pr-1" id="smart-link-results-list">
            <div class="py-8 text-center text-xs text-stone-400">
                Ketik judul untuk mencari artikel...
            </div>
        </div>

        {{-- Footer hint --}}
        <div class="mt-4 flex items-center justify-between border-t border-stone-100 pt-3 text-[11px] text-stone-400 dark:border-white/[0.06]">
            <span>Tips: Pilih teks terlebih dahulu untuk menjadikannya anchor link langsung.</span>
            <kbd class="rounded border border-stone-200 bg-stone-100 px-1.5 py-0.5 font-mono text-[10px] text-stone-500 dark:border-white/10 dark:bg-white/[0.04]">ESC</kbd>
        </div>
    </div>
</div>
