@extends('layouts.admin')

@section('title', 'Pengguna')
@section('page-title', 'Pengguna')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-stone-500 dark:text-stone-400">Kelola akun, persetujuan hak akses, dan peran pengguna</p>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    {{-- Filter & Search --}}
    <form method="GET" class="mt-6 flex flex-wrap items-center gap-2">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari nama atau email..."
            class="rounded-xl border border-stone-200 bg-white px-3.5 py-2 text-sm text-stone-700 outline-none transition placeholder:text-stone-400 focus:border-[#1e3a5f] dark:border-white/10 dark:bg-[#171716] dark:text-stone-300 dark:placeholder:text-stone-500"
        >
        <select name="role" class="rounded-xl border border-stone-200 bg-white px-3 py-2 text-sm text-stone-600 outline-none transition focus:border-[#1e3a5f] dark:border-white/10 dark:bg-[#171716] dark:text-stone-400">
            <option value="">Semua Peran</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="penulis" {{ request('role') === 'penulis' ? 'selected' : '' }}>Penulis</option>
        </select>
        <select name="status" class="rounded-xl border border-stone-200 bg-white px-3 py-2 text-sm text-stone-600 outline-none transition focus:border-[#1e3a5f] dark:border-white/10 dark:bg-[#171716] dark:text-stone-400">
            <option value="">Semua Status</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
        </select>
        <button type="submit" class="rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-600 transition hover:border-stone-300 hover:text-stone-900 dark:border-white/10 dark:bg-[#171716] dark:text-stone-400 dark:hover:text-white">Filter</button>
        @if (request()->hasAny(['search', 'role', 'status']))
            <a href="{{ route('admin.pengguna.index') }}" class="text-xs text-red-500 hover:underline">Reset</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="mt-4 overflow-hidden rounded-2xl border border-stone-200/60 bg-white dark:border-white/[0.06] dark:bg-[#171716]">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-stone-200 dark:divide-white/[0.06]">
                <thead>
                    <tr class="bg-stone-50 text-left text-[11px] font-bold uppercase tracking-wider text-stone-400 dark:bg-white/[0.02] dark:text-stone-500">
                        <th class="px-5 py-3.5">Pengguna</th>
                        <th class="px-5 py-3.5">Peran</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Bergabung</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 dark:divide-white/[0.06]">
                    @forelse ($users as $user)
                        <tr class="transition hover:bg-stone-50/60 dark:hover:bg-white/[0.02]">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-stone-100 font-serif text-sm font-bold text-stone-500 dark:bg-white/[0.05] dark:text-stone-400">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold">{{ $user->name }}</p>
                                        <p class="truncate text-xs text-stone-400 dark:text-stone-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                @if ($user->id === auth()->id())
                                    <span class="rounded-full bg-[#1e3a5f]/10 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-[#1e3a5f] dark:bg-[#5b9bd5]/10 dark:text-[#5b9bd5]">
                                        {{ $user->roles->first()?->name ?? 'Admin' }} (Anda)
                                    </span>
                                @else
                                    <form method="POST" action="{{ route('admin.pengguna.role', $user) }}" class="inline-flex items-center">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" onchange="this.form.submit()" class="rounded-lg border border-stone-200 bg-white px-2 py-1 text-xs text-stone-700 outline-none transition focus:border-[#1e3a5f] dark:border-white/10 dark:bg-[#171716] dark:text-stone-300">
                                            <option value="pembaca" {{ $user->roles->isEmpty() ? 'selected' : '' }}>Pembaca</option>
                                            <option value="penulis" {{ $user->hasRole('penulis') ? 'selected' : '' }}>Penulis</option>
                                            <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>Admin</option>
                                        </select>
                                    </form>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if ($user->is_approved)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Disetujui
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-amber-600 dark:bg-amber-500/10 dark:text-amber-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                        Menunggu
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs text-stone-500 dark:text-stone-400">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    @if (! $user->is_approved)
                                        <form method="POST" action="{{ route('admin.pengguna.approve', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-500/10 px-3 py-1.5 text-xs font-semibold text-emerald-600 transition hover:bg-emerald-500/20 dark:text-emerald-400" title="Setujui akun">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                                Setujui
                                            </button>
                                        </form>
                                    @endif
                                    @if ($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.pengguna.destroy', $user) }}" onsubmit="return confirm('Yakin ingin menghapus akun {{ $user->name }}?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg p-1.5 text-stone-400 transition hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-500/10 dark:hover:text-red-400" title="Hapus akun">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-sm text-stone-400 dark:text-stone-500">Belum ada pengguna</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="border-t border-stone-200/60 p-4 dark:border-white/[0.06]">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
