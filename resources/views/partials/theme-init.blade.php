<script>
    (function () {
        var theme;
        try { theme = localStorage.getItem('theme'); } catch (e) {}
        if (theme === 'dark' || (!theme && matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    })();
</script>
