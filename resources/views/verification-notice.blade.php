@extends('layouts.auth')

@section('title', 'Verifikasi Email')
@section('heading', 'Periksa Email Anda')
@section('subtitle', 'Verifikasi alamat email untuk mengaktifkan akun Anda.')

@section('form')
    <div class="mt-8">
        <p class="text-sm leading-relaxed text-stone-500 dark:text-stone-400">
            Kami telah mengirim tautan verifikasi ke
            <strong class="text-stone-700 dark:text-stone-200">{{ auth()->user()->email }}</strong>.
            Klik tautan di email tersebut untuk memverifikasi akun Anda. Tautan berlaku selama 60 menit.
        </p>

        <form method="POST" action="{{ route('verification.resend') }}" class="mt-6">
            @csrf
            <button
                type="submit"
                class="w-full rounded-lg bg-stone-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-stone-800 active:scale-[0.98] dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200"
            >
                Kirim Ulang Tautan
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button
                type="submit"
                class="w-full rounded-lg border border-stone-200 px-4 py-3 text-sm font-medium text-stone-600 transition hover:border-stone-300 hover:text-stone-900 dark:border-white/10 dark:text-stone-400 dark:hover:border-white/20 dark:hover:text-white"
            >
                Keluar
            </button>
        </form>
    </div>
@endsection