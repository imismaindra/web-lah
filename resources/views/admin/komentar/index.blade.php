@extends('layouts.admin')

@section('title', 'Komentar')
@section('page-title', 'Komentar')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-stone-500 dark:text-stone-400">Kelola dan moderasi komentar pengunjung pada artikel</p>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter & Bulk Bar --}}
    <div class="mt-6 flex flex-wrap items-center justify-between gap-4">
        <form method="GET" class="flex gap-2">
            <select name="status" class="rounded-xl border border-stone-200 bg-white px-3 py-2 text-sm text-stone-600 outline-none transition focus:border-[#1e3a5f] focus:ring-2 focus:ring-[#1e3a5f]/10 dark:border-white/10 dark:bg-[#171716] dark:text-stone-400 dark:focus:border-stone-400">
                <option value="">Semua Status</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
            <button type="submit" class="rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-600 transition hover:border-stone-300 hover:text-stone-900 dark:border-white/10 dark:bg-[#171716] dark:text-stone-400 dark:hover:border-white/20 dark:hover:text-white">Filter</button>
        </form>

        <div id="bulk-actions" class="hidden flex items-center gap-2">
            <span id="selected-count" class="text-xs font-semibold text-stone-500 dark:text-stone-400">0 dipilih</span>
            <button type="button" onclick="submitBulk('approve')" class="rounded-xl bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">Setujui Terpilih</button>
            <button type="button" onclick="submitBulk('delete')" class="rounded-xl bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700">Hapus Terpilih</button>
        </div>
    </div>

    {{-- Table --}}
    <form id="bulk-form" method="POST" action="{{ route('admin.komentar.bulk') }}">
        @csrf
        <input type="hidden" name="action" id="bulk-action-input" value="">

        <div class="mt-4 overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-stone-200 dark:divide-white/[0.06]">
                    <thead>
                        <tr class="bg-stone-50 text-left text-[11px] font-bold uppercase tracking-wider text-stone-400 dark:bg-white/[0.02] dark:text-stone-500">
                            <th class="w-10 px-4 py-3.5 text-center">
                                <input type="checkbox" id="check-all" class="h-4 w-4 rounded border-stone-300 accent-[#1e3a5f]">
                            </th>
                            <th class="px-5 py-3.5">Komentar</th>
                            <th class="px-5 py-3.5">Nama</th>
                            <th class="px-5 py-3.5">Artikel</th>
                            <th class="px-5 py-3.5">Waktu</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200 dark:divide-white/[0.06]">
                        @forelse ($komentars as $komentar)
                            <tr class="transition hover:bg-stone-50/60 dark:hover:bg-white/[0.02]">
                                <td class="px-4 py-4 text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $komentar->id }}" class="item-checkbox h-4 w-4 rounded border-stone-300 accent-[#1e3a5f]">
                                </td>
                                <td class="max-w-xs px-5 py-4">
                                    <p class="line-clamp-2 text-sm text-stone-700 dark:text-stone-300">{{ $komentar->isi }}</p>
                                    @if ($komentar->parent_id)
                                        <p class="mt-1 text-[11px] font-semibold text-stone-400 dark:text-stone-500">Balasan</p>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-sm font-semibold">
                                    {{ $komentar->displayName() }}
                                    @if ($komentar->status !== 'published')
                                        <span class="ml-1 rounded-full bg-amber-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-amber-600 dark:bg-amber-500/10 dark:text-amber-400">Pending</span>
                                    @endif
                                </td>
                                <td class="max-w-[180px] px-5 py-4">
                                    @if ($komentar->artikel)
                                        <a href="{{ route('artikel.show', $komentar->artikel) }}" target="_blank" class="line-clamp-1 text-sm text-[#1e3a5f] transition hover:text-[#16304a] dark:text-stone-300 dark:hover:text-white">{{ $komentar->artikel->judul }}</a>
                                    @else
                                        <span class="text-sm text-stone-400">Artikel dihapus</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-sm text-stone-500 dark:text-stone-400">{{ $komentar->created_at->translatedFormat('d M Y, H:i') }}</td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        @if ($komentar->status !== 'published')
                                            <button type="submit" formaction="{{ route('admin.komentar.approve', $komentar) }}" class="rounded-lg p-1.5 text-stone-400 transition hover:bg-emerald-50 hover:text-emerald-600 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-400" title="Setujui komentar">
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
                                            </button>
                                        @endif
                                        <button type="submit" formaction="{{ route('admin.komentar.destroy', $komentar) }}" onclick="return confirm('Yakin ingin menghapus komentar ini?')" class="rounded-lg p-1.5 text-stone-400 transition hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-500/10 dark:hover:text-red-400" title="Hapus komentar">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-sm text-stone-400 dark:text-stone-500">Belum ada komentar</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </form>

    {{-- Pagination --}}
    @if ($komentars->hasPages())
        <div class="mt-8">
            {{ $komentars->links() }}
        </div>
    @endif

    @push('scripts')
    <script>
        const checkAll = document.getElementById('check-all');
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const bulkActions = document.getElementById('bulk-actions');
        const selectedCount = document.getElementById('selected-count');
        const bulkActionInput = document.getElementById('bulk-action-input');
        const bulkForm = document.getElementById('bulk-form');

        function updateBulk() {
            const checked = document.querySelectorAll('.item-checkbox:checked');
            if (checked.length > 0) {
                bulkActions.classList.remove('hidden');
                selectedCount.textContent = checked.length + ' dipilih';
            } else {
                bulkActions.classList.add('hidden');
            }
        }

        checkAll?.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulk();
        });

        checkboxes.forEach(cb => cb.addEventListener('change', updateBulk));

        function submitBulk(action) {
            if (action === 'delete' && !confirm('Hapus semua komentar yang dipilih?')) {
                return;
            }
            bulkActionInput.value = action;
            bulkForm.submit();
        }
    </script>
    @endpush
@endsection
