@extends('layouts.auth')

@section('title', 'Atur Ulang Kata Sandi')
@section('heading', 'Atur Ulang Kata Sandi')
@section('subtitle', 'Buat kata sandi baru untuk akun Anda.')

@section('form')
    <form method="POST" action="{{ route('password.store') }}" class="mt-8 space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

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
            <label for="password" class="block text-sm font-semibold text-stone-700 dark:text-stone-300">Kata Sandi Baru</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Minimal 8 karakter"
                class="mt-2 block w-full rounded-lg border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder:text-stone-400 focus:border-[#1e3a5f] focus:outline-none focus:ring-1 focus:ring-[#1e3a5f] dark:border-white/10 dark:bg-white/[0.03] dark:text-[#e5e5e3] dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]"
            >
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-stone-700 dark:text-stone-300">Konfirmasi Kata Sandi</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Ulangi kata sandi baru"
                class="mt-2 block w-full rounded-lg border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder:text-stone-400 focus:border-[#1e3a5f] focus:outline-none focus:ring-1 focus:ring-[#1e3a5f] dark:border-white/10 dark:bg-white/[0.03] dark:text-[#e5e5e3] dark:placeholder:text-stone-500 dark:focus:border-[#5b9bd5] dark:focus:ring-[#5b9bd5]"
            >
        </div>

        <button
            type="submit"
            class="w-full rounded-lg bg-stone-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-stone-800 active:scale-[0.98] dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200"
        >
            Atur Ulang Kata Sandi
        </button>
    </form>
@endsection