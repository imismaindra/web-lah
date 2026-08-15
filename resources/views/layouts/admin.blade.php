<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Dashboard') — {{ config('app.name', 'Look at History') }}</title>

        <link rel="icon" href="{{ asset('favicon.ico') }}">

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        @stack('styles')

        <style>
            .sidebar-link.active {
                background: rgba(30, 58, 95, 0.08);
                color: #1e3a5f;
            }
            :is(.dark) .sidebar-link.active {
                background: rgba(91, 155, 213, 0.1);
                color: #5b9bd5;
            }

            /* Collapsed sidebar */
            #sidebar[data-collapsed="true"] {
                width: 5rem;
            }
            #sidebar[data-collapsed="true"] .sidebar-label {
                display: none;
            }
            #sidebar[data-collapsed="true"] .sidebar-tooltip {
                display: block;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.15s;
            }
            #sidebar[data-collapsed="true"] .sidebar-link:hover .sidebar-tooltip {
                opacity: 1;
            }
            #sidebar[data-collapsed="true"] #collapse-icon {
                transform: rotate(180deg);
            }
            #sidebar[data-collapsed="true"] nav {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            #sidebar[data-collapsed="true"] .sidebar-link {
                justify-content: center;
                padding-left: 0;
                padding-right: 0;
            }
            #sidebar[data-collapsed="true"] .sidebar-link svg {
                margin: 0;
            }

            /* Collapsed main content */
            .main-collapsed {
                margin-left: 5rem !important;
            }
        </style>
    </head>
    <body class="bg-stone-50 text-stone-900 dark:bg-[#0f0f0e] dark:text-[#e5e5e3] font-sans antialiased">
        <div class="flex min-h-[100dvh]">
            {{-- Sidebar --}}
            <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 flex w-64 -translate-x-full flex-col border-r border-stone-200/80 bg-white transition-all duration-200 dark:border-white/[0.06] dark:bg-[#171716] lg:translate-x-0" data-collapsed="false">
                {{-- Logo --}}
                <div class="flex h-16 items-center justify-between border-b border-stone-200/80 px-5 dark:border-white/[0.06]">
                    <a href="/" class="flex items-center gap-3 sidebar-label">
                        <img src="{{ asset('logo_LAH.jpg') }}" alt="{{ config('app.name', 'Look at History') }}" class="h-8 w-8 flex-shrink-0 rounded-full object-cover ring-1 ring-stone-200 dark:ring-white/10">
                        <span class="font-serif text-base font-bold tracking-tight">Look at History</span>
                    </a>
                    <button id="collapse-btn" class="hidden rounded-lg p-1.5 text-stone-400 transition hover:bg-stone-100 hover:text-stone-600 lg:block dark:hover:bg-white/[0.05] dark:hover:text-stone-300" title="Minimalkan sidebar">
                        <svg id="collapse-icon" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m11 17-5-5 5-5"/><path d="m18 17-5-5 5-5"/></svg>
                    </button>
                </div>

                {{-- Nav --}}
                <nav class="flex-1 overflow-y-auto overflow-x-hidden px-3 py-4">
                    <div class="space-y-1">
                        <p class="sidebar-label mb-2 px-3 text-[10px] font-bold uppercase tracking-widest text-stone-400 dark:text-stone-500">Menu</p>

                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link group relative flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-stone-600 transition hover:bg-stone-50 hover:text-stone-900 dark:text-stone-400 dark:hover:bg-white/[0.03] dark:hover:text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" title="Dashboard">
                            <svg class="h-4 w-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                            <span class="sidebar-label">Dashboard</span>
                            <span class="sidebar-tooltip pointer-events-none absolute left-full ml-2 hidden whitespace-nowrap rounded-lg bg-stone-900 px-3 py-1.5 text-xs font-medium text-white shadow-lg group-hover:block dark:bg-stone-700">Dashboard</span>
                        </a>

                        <a href="{{ route('admin.artikel.index') }}" class="sidebar-link group relative flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-stone-600 transition hover:bg-stone-50 hover:text-stone-900 dark:text-stone-400 dark:hover:bg-white/[0.03] dark:hover:text-white {{ request()->routeIs('admin.artikel.*') ? 'active' : '' }}" title="Artikel">
                            <svg class="h-4 w-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                            <span class="sidebar-label">Artikel</span>
                            <span class="sidebar-tooltip pointer-events-none absolute left-full ml-2 hidden whitespace-nowrap rounded-lg bg-stone-900 px-3 py-1.5 text-xs font-medium text-white shadow-lg group-hover:block dark:bg-stone-700">Artikel</span>
                        </a>

                        @if (auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.komentar.index') }}" class="sidebar-link group relative flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-stone-600 transition hover:bg-stone-50 hover:text-stone-900 dark:text-stone-400 dark:hover:bg-white/[0.03] dark:hover:text-white {{ request()->routeIs('admin.komentar.*') ? 'active' : '' }}" title="Komentar">
                            <svg class="h-4 w-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            <span class="sidebar-label">Komentar</span>
                            <span class="sidebar-tooltip pointer-events-none absolute left-full ml-2 hidden whitespace-nowrap rounded-lg bg-stone-900 px-3 py-1.5 text-xs font-medium text-white shadow-lg group-hover:block dark:bg-stone-700">Komentar</span>
                        </a>
                    @endif

                        @if (auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.kategori.index') }}" class="sidebar-link group relative flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-stone-600 transition hover:bg-stone-50 hover:text-stone-900 dark:text-stone-400 dark:hover:bg-white/[0.03] dark:hover:text-white {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}" title="Kategori">
                            <svg class="h-4 w-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10h10V2Z"/><path d="M22 12H12v10h10V12Z"/><path d="M22 2H12v5h10V2Z"/><path d="M7 12H2v10h5V12Z"/></svg>
                            <span class="sidebar-label">Kategori</span>
                            <span class="sidebar-tooltip pointer-events-none absolute left-full ml-2 hidden whitespace-nowrap rounded-lg bg-stone-900 px-3 py-1.5 text-xs font-medium text-white shadow-lg group-hover:block dark:bg-stone-700">Kategori</span>
                        </a>
                    @endif

                        @if (auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.era.index') }}" class="sidebar-link group relative flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-stone-600 transition hover:bg-stone-50 hover:text-stone-900 dark:text-stone-400 dark:hover:bg-white/[0.03] dark:hover:text-white {{ request()->routeIs('admin.era.*') ? 'active' : '' }}" title="Era">
                            <svg class="h-4 w-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span class="sidebar-label">Era</span>
                            <span class="sidebar-tooltip pointer-events-none absolute left-full ml-2 hidden whitespace-nowrap rounded-lg bg-stone-900 px-3 py-1.5 text-xs font-medium text-white shadow-lg group-hover:block dark:bg-stone-700">Era</span>
                        </a>
                    @endif

                        @if (auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.topik.index') }}" class="sidebar-link group relative flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-stone-600 transition hover:bg-stone-50 hover:text-stone-900 dark:text-stone-400 dark:hover:bg-white/[0.03] dark:hover:text-white {{ request()->routeIs('admin.topik.*') ? 'active' : '' }}" title="Topik">
                            <svg class="h-4 w-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16.2 7.8l-2 6.3-6.4 2.1 2-6.3z"/></svg>
                            <span class="sidebar-label">Topik</span>
                            <span class="sidebar-tooltip pointer-events-none absolute left-full ml-2 hidden whitespace-nowrap rounded-lg bg-stone-900 px-3 py-1.5 text-xs font-medium text-white shadow-lg group-hover:block dark:bg-stone-700">Topik</span>
                        </a>
                    @endif

                        @if (auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.penulis.index') }}" class="sidebar-link group relative flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-stone-600 transition hover:bg-stone-50 hover:text-stone-900 dark:text-stone-400 dark:hover:bg-white/[0.03] dark:hover:text-white {{ request()->routeIs('admin.penulis.*') ? 'active' : '' }}" title="Penulis">
                            <svg class="h-4 w-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <span class="sidebar-label">Penulis</span>
                            <span class="sidebar-tooltip pointer-events-none absolute left-full ml-2 hidden whitespace-nowrap rounded-lg bg-stone-900 px-3 py-1.5 text-xs font-medium text-white shadow-lg group-hover:block dark:bg-stone-700">Penulis</span>
                        </a>
                    @endif

                        @if (auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.pengguna.index') }}" class="sidebar-link group relative flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-stone-600 transition hover:bg-stone-50 hover:text-stone-900 dark:text-stone-400 dark:hover:bg-white/[0.03] dark:hover:text-white {{ request()->routeIs('admin.pengguna.*') ? 'active' : '' }}" title="Pengguna">
                            <svg class="h-4 w-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <span class="sidebar-label">Pengguna</span>
                            <span class="sidebar-tooltip pointer-events-none absolute left-full ml-2 hidden whitespace-nowrap rounded-lg bg-stone-900 px-3 py-1.5 text-xs font-medium text-white shadow-lg group-hover:block dark:bg-stone-700">Pengguna</span>
                        </a>
                    @endif

                        @if (auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.newsletter.index') }}" class="sidebar-link group relative flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-stone-600 transition hover:bg-stone-50 hover:text-stone-900 dark:text-stone-400 dark:hover:bg-white/[0.03] dark:hover:text-white {{ request()->routeIs('admin.newsletter.*') ? 'active' : '' }}" title="Buletin">
                            <svg class="h-4 w-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            <span class="sidebar-label">Buletin</span>
                            <span class="sidebar-tooltip pointer-events-none absolute left-full ml-2 hidden whitespace-nowrap rounded-lg bg-stone-900 px-3 py-1.5 text-xs font-medium text-white shadow-lg group-hover:block dark:bg-stone-700">Buletin</span>
                        </a>
                    @endif

                        <a href="{{ route('profil.edit') }}" class="sidebar-link group relative flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-stone-600 transition hover:bg-stone-50 hover:text-stone-900 dark:text-stone-400 dark:hover:bg-white/[0.03] dark:hover:text-white {{ request()->routeIs('profil.*') ? 'active' : '' }}" title="Profil">
                            <svg class="h-4 w-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <span class="sidebar-label">Profil</span>
                            <span class="sidebar-tooltip pointer-events-none absolute left-full ml-2 hidden whitespace-nowrap rounded-lg bg-stone-900 px-3 py-1.5 text-xs font-medium text-white shadow-lg group-hover:block dark:bg-stone-700">Profil</span>
                        </a>
                    </div>
                </nav>

                {{-- User --}}
                <div class="border-t border-stone-200/80 p-4 dark:border-white/[0.06]">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-stone-100 font-serif text-sm font-bold text-stone-500 dark:bg-white/[0.05] dark:text-stone-400">
                            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="sidebar-label min-w-0 flex-1 overflow-hidden">
                            <p class="truncate text-sm font-semibold">{{ auth()->user()->name ?? 'Admin' }}</p>
                            <p class="truncate text-xs text-stone-400 dark:text-stone-500">{{ auth()->user()->email ?? 'admin@lah.id' }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="sidebar-label">
                            @csrf
                            <button type="submit" class="rounded-lg p-1.5 text-stone-400 transition hover:bg-stone-100 hover:text-stone-600 dark:hover:bg-white/[0.05] dark:hover:text-stone-300" title="Keluar">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            {{-- Overlay --}}
            <div id="sidebar-overlay" class="fixed inset-0 z-20 hidden bg-black/50 lg:hidden"></div>

            {{-- Main --}}
            <div class="flex-1 lg:ml-64">
                {{-- Top Bar --}}
                <header class="sticky top-0 z-10 flex h-16 items-center justify-between border-b border-stone-200/80 bg-white/80 px-5 backdrop-blur-xl dark:border-white/[0.06] dark:bg-[#171716]/80 sm:px-8">
                    <div class="flex items-center gap-3">
                        <button id="menu-btn" class="rounded-lg p-1.5 text-stone-400 hover:bg-stone-100 hover:text-stone-600 lg:hidden dark:hover:bg-white/[0.05] dark:hover:text-stone-300">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                        </button>
                        <h1 class="font-serif text-lg font-bold tracking-tight">@yield('page-title', 'Dashboard')</h1>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="/" target="_blank" class="rounded-lg border border-stone-200 px-3 py-1.5 text-xs font-semibold text-stone-500 transition hover:border-stone-300 hover:text-stone-900 dark:border-white/10 dark:text-stone-400 dark:hover:border-white/20 dark:hover:text-white">
                            Lihat Situs
                        </a>
                    </div>
                </header>

                {{-- Content --}}
                <main class="p-5 sm:p-8">
                    @yield('content')
                </main>
            </div>
        </div>

        <script>
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const menuBtn = document.getElementById('menu-btn');
            const collapseBtn = document.getElementById('collapse-btn');
            const mainContent = document.querySelector('.flex-1.lg\\:ml-64');

            // Load saved state
            const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            if (isCollapsed && window.innerWidth >= 1024) {
                sidebar.dataset.collapsed = 'true';
                mainContent?.classList.add('main-collapsed');
            }

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                overlay.classList.remove('hidden');
            }

            function closeSidebar() {
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }

            function toggleCollapse() {
                const collapsed = sidebar.dataset.collapsed === 'true';
                sidebar.dataset.collapsed = collapsed ? 'false' : 'true';
                localStorage.setItem('sidebar-collapsed', !collapsed);
                mainContent?.classList.toggle('main-collapsed');
            }

            menuBtn.addEventListener('click', openSidebar);
            overlay.addEventListener('click', closeSidebar);
            collapseBtn.addEventListener('click', toggleCollapse);
        </script>

        @stack('scripts')
    </body>
</html>
