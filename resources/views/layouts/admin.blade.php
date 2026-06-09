<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Admin Dashboard | Sawah Pulo Hub' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Tailwind CSS & Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 min-h-screen flex antialiased" x-data="adminLayoutController()" x-init="checkAdmin()">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 text-slate-400 border-r border-slate-800 flex flex-col fixed inset-y-0 left-0 z-40 transition-transform duration-300 md:translate-x-0" :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
            <!-- Sidebar Header -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <span class="text-lg font-extrabold tracking-tight bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent">
                        SAWAH PULO
                    </span>
                    <span class="text-[10px] px-2 py-0.5 font-semibold bg-emerald-950 text-emerald-300 rounded-full">ADMIN</span>
                </a>
                <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all" :class="activeRoute === 'dashboard' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'hover:bg-slate-800 hover:text-slate-200'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
                    Ringkasan Dashboard
                </a>

                <a href="{{ route('admin.tickets') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all" :class="activeRoute === 'tickets' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'hover:bg-slate-800 hover:text-slate-200'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    Tiket Wisata
                </a>

                <a href="{{ route('admin.facilities') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all" :class="activeRoute === 'facilities' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'hover:bg-slate-800 hover:text-slate-200'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Fasilitas Wisata
                </a>

                <a href="{{ route('admin.articles') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all" :class="activeRoute === 'articles' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'hover:bg-slate-800 hover:text-slate-200'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    Artikel Edukasi
                </a>

                <a href="{{ route('admin.orders') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all" :class="activeRoute === 'orders' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'hover:bg-slate-800 hover:text-slate-200'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Verifikasi Pembayaran
                </a>

                <a href="{{ route('admin.scan') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all" :class="activeRoute === 'scan' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'hover:bg-slate-800 hover:text-slate-200'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m0 14v1m8-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M9 9h6v6H9V9z"></path></svg>
                    Scan Tiket Masuk
                </a>

                <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all" :class="activeRoute === 'settings' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'hover:bg-slate-800 hover:text-slate-200'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Pengaturan Global
                </a>
            </nav>

            <!-- Sidebar Footer / Admin Profile -->
            <div class="p-4 border-t border-slate-800">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 flex-grow min-w-0 group hover:opacity-90">
                        <div class="w-9 h-9 rounded-xl bg-emerald-600 group-hover:bg-emerald-500 text-white font-bold flex items-center justify-center transition-colors" x-text="userInitials"></div>
                        <div class="flex-grow min-w-0">
                            <p class="text-sm font-semibold text-slate-200 truncate group-hover:text-emerald-400 transition-colors" x-text="userName"></p>
                            <p class="text-xs text-slate-500 truncate">Administrator</p>
                        </div>
                    </a>
                    <button @click="logout" class="p-1.5 text-slate-500 hover:text-red-400 hover:bg-slate-800 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="flex-1 flex flex-col md:pl-64">
            
            <!-- Navbar -->
            <header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between px-6 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="md:hidden p-2 text-slate-500 hover:text-slate-800 dark:hover:text-white rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">{{ $header ?? 'Panel Admin' }}</h2>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Lihat Situs Wisatawan
                    </a>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-grow p-6 sm:p-8">
                {{ $slot }}
            </main>
        </div>

        <!-- Layout controller script -->
        <script>
            function adminLayoutController() {
                return {
                    sidebarOpen: false,
                    isLoggedIn: false,
                    userName: '',
                    userInitials: '',
                    activeRoute: '{{ $activeRoute ?? 'dashboard' }}',
                    checkAdmin() {
                        const token = localStorage.getItem('access_token');
                        const profileStr = localStorage.getItem('user_profile');
                        
                        if (!token || !profileStr) {
                            window.location.href = '/login';
                            return;
                        }

                        const profile = JSON.parse(profileStr);
                        
                        // Verify role admin
                        const roles = profile.roles || [];
                        const isAdmin = roles.some(role => role.slug === 'admin');
                        
                        if (!isAdmin) {
                            // Regular tourists are redirected to homepage
                            window.location.href = '/';
                            return;
                        }

                        this.isLoggedIn = true;
                        this.userName = profile.name;
                        
                        // Get initials
                        const names = profile.name.split(' ');
                        this.userInitials = names.map(n => n[0]).slice(0, 2).join('').toUpperCase();
                    },
                    logout() {
                        const token = localStorage.getItem('access_token');
                        axios.post('/api/logout', {}, {
                            headers: {
                                'Authorization': `Bearer ${token}`
                            }
                        }).finally(() => {
                            localStorage.removeItem('access_token');
                            localStorage.removeItem('user_profile');
                            window.location.href = '/login';
                        });
                    }
                }
            }
        </script>
    </body>
</html>
