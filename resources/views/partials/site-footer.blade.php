<footer class="mt-16 border-t border-stone-200/80 dark:border-white/[0.06]">
    <div class="mx-auto flex max-w-[1400px] flex-col items-center justify-between gap-4 px-5 py-8 text-sm text-stone-400 sm:flex-row sm:px-8 dark:text-stone-500">
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'Look at History') }}.</p>
        <div class="flex items-center gap-5">
            <a href="{{ route('kontak') }}" class="transition hover:text-stone-600 dark:hover:text-stone-300">Kontak</a>
            <a href="{{ route('privasi') }}" class="transition hover:text-stone-600 dark:hover:text-stone-300">Kebijakan Privasi</a>
        </div>
    </div>
</footer>
