@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center gap-1">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex h-8.5 w-8.5 items-center justify-center rounded-xl border border-stone-200/50 bg-stone-50/50 text-stone-300 cursor-not-allowed dark:border-white/[0.06] dark:bg-white/[0.02] dark:text-stone-600" aria-disabled="true">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex h-8.5 w-8.5 items-center justify-center rounded-xl border border-stone-200 bg-white text-stone-600 transition hover:bg-stone-100 hover:text-stone-900 dark:border-white/10 dark:bg-[#171716] dark:text-stone-400 dark:hover:bg-white/5 dark:hover:text-white" aria-label="Halaman Sebelumnya">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="inline-flex h-8.5 w-6 items-center justify-center text-xs text-stone-400 dark:text-stone-600">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="inline-flex h-8.5 min-w-[2.125rem] items-center justify-center rounded-xl px-2 text-xs font-bold bg-[#1e3a5f] text-white shadow-xs dark:bg-[#5b9bd5] dark:text-[#0f0f0e]">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="inline-flex h-8.5 min-w-[2.125rem] items-center justify-center rounded-xl border border-stone-200 bg-white px-2 text-xs font-semibold text-stone-600 transition hover:bg-stone-100 hover:text-stone-900 dark:border-white/10 dark:bg-[#171716] dark:text-stone-400 dark:hover:bg-white/5 dark:hover:text-white" aria-label="Halaman {{ $page }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex h-8.5 w-8.5 items-center justify-center rounded-xl border border-stone-200 bg-white text-stone-600 transition hover:bg-stone-100 hover:text-stone-900 dark:border-white/10 dark:bg-[#171716] dark:text-stone-400 dark:hover:bg-white/5 dark:hover:text-white" aria-label="Halaman Berikutnya">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        @else
            <span class="inline-flex h-8.5 w-8.5 items-center justify-center rounded-xl border border-stone-200/50 bg-stone-50/50 text-stone-300 cursor-not-allowed dark:border-white/[0.06] dark:bg-white/[0.02] dark:text-stone-600" aria-disabled="true">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </span>
        @endif
    </nav>
@endif
