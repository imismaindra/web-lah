@extends('layouts.auth')

@section('title', 'Masuk ke Panel')
@section('heading', 'Masuk ke Panel')
@section('subtitle', 'Halaman khusus admin & penulis. Pengguna biasa menggunakan halaman masuk pembaca.')

@section('form')
    <form method="POST" action="{{ route('panel.login') }}" class="mt-8 space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-stone-700 dark:text-stone-300">Alamat Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="email"
                placeholder="nama@contoh.com"
                class="mt-2 block w-full rounded-lg border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder:text-stone-400 focus:border-[#1e3a5f] focus:outline-none focus:ring-1 focus:ring-[#1e3a5f] dark:border-white/10 dark:bg-white/[0.03] dark:text-[#e5e5e3] dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]"
            >
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-stone-700 dark:text-stone-300">Kata Sandi</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
                class="mt-2 block w-full rounded-lg border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder:text-stone-400 focus:border-[#1e3a5f] focus:outline-none focus:ring-1 focus:ring-[#1e3a5f] dark:border-white/10 dark:bg-white/[0.03] dark:text-[#e5e5e3] dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]"
            >
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center gap-2">
                <input
                    type="checkbox"
                    id="remember_me"
                    name="remember"
                    class="h-4 w-4 rounded border-stone-300 text-[#1e3a5f] focus:ring-[#1e3a5f] dark:border-white/20 dark:text-[#5b9bd5] dark:focus:ring-[#5b9bd5]"
                >
                <span class="text-sm text-stone-500 dark:text-stone-400">Ingat saya</span>
            </label>

            <a href="{{ route('password.request') }}" class="text-sm font-semibold text-[#1e3a5f] transition hover:text-[#16304a] dark:text-[#5b9bd5] dark:hover:text-[#7ab3e0]">
                Lupa sandi?
            </a>
        </div>

        <button
            type="submit"
            class="w-full rounded-lg bg-stone-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-stone-800 active:scale-[0.98] dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200"
        >
            Masuk ke Panel
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-stone-400 dark:text-stone-500">
        Bukan admin/penulis?
        <a href="{{ route('login') }}" class="font-semibold text-[#1e3a5f] transition hover:text-[#16304a] dark:text-[#5b9bd5] dark:hover:text-[#7ab3e0]">Masuk sebagai pembaca</a>
    </p>
@endsection