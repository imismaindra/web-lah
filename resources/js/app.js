// Tema gelap/terang — default mengikuti OS, pilihan user tersimpan di localStorage.
const root = document.documentElement;
const themeToggle = document.getElementById('theme-toggle');
const syncThemeIcons = () => {
    const dark = root.classList.contains('dark');
    document.getElementById('theme-icon-moon')?.classList.toggle('hidden', dark);
    document.getElementById('theme-icon-sun')?.classList.toggle('hidden', !dark);
};

if (themeToggle) {
    syncThemeIcons();
    themeToggle.addEventListener('click', () => {
        const dark = root.classList.toggle('dark');
        try { localStorage.setItem('theme', dark ? 'dark' : 'light'); } catch {}

        syncThemeIcons();
    });
}

// Jalan tegas: anchor dari menu mobile & nav (/#kategori, /#artikel).
document.querySelectorAll('a[href^="/#"]').forEach((link) => {
    link.addEventListener('click', (e) => {
        const id = decodeURIComponent(link.getAttribute('href').slice(2));
        const target = document.getElementById(id);
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
