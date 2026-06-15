<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Sawah Pulo Hub' }}</title>

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
        </style>
    </head>
    <body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 min-h-screen flex flex-col antialiased">
        
        <!-- Header / Navbar -->
        <header x-data="navController()" class="sticky top-0 z-50 backdrop-blur-md bg-white/80 dark:bg-slate-900/80 border-b border-slate-100 dark:border-slate-800 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    
                    <!-- Logo / Brand -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                            <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-emerald-600 to-teal-500 bg-clip-text text-transparent group-hover:from-emerald-500 group-hover:to-teal-400 transition-all">
                                SAWAH PULO
                            </span>
                            <span class="text-xs px-2 py-0.5 font-semibold bg-emerald-100 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 rounded-full">HUB</span>
                        </a>
                    </div>

                    <!-- Navigation Links (Desktop) -->
                    <nav class="hidden md:flex items-center gap-6">
                        <a href="{{ route('home') }}" class="text-sm font-medium hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Beranda</a>
                        <a href="{{ route('about') }}" class="text-sm font-medium hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Profil Wisata</a>
                        <a href="{{ route('facilities') }}" class="text-sm font-medium hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Fasilitas</a>
                        <a href="{{ route('tickets.index') }}" class="text-sm font-medium hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Tiket</a>
                        <a href="{{ route('articles.index') }}" class="text-sm font-medium hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Artikel</a>
                    </nav>

                    <!-- User Actions (Desktop) -->
                    <div class="hidden md:flex items-center gap-4">
                        <!-- Guest Mode -->
                        <template x-if="!isLoggedIn">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white px-3 py-2">Masuk</a>
                                <a href="{{ route('register') }}" class="text-sm font-semibold bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-xl shadow-md shadow-emerald-500/10 hover:shadow-emerald-500/20 transition-all duration-200">Daftar</a>
                            </div>
                        </template>

                        <!-- Logged-in Mode -->
                        <template x-if="isLoggedIn">
                            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                <button @click="open = !open" class="flex items-center gap-2 bg-slate-100 dark:bg-slate-800/80 hover:bg-slate-200 dark:hover:bg-slate-800 px-4 py-2 rounded-xl transition-all duration-200">
                                    <div class="w-6 h-6 rounded-full bg-emerald-600 text-white text-xs font-bold flex items-center justify-center" x-text="userInitials"></div>
                                    <span class="text-sm font-medium" x-text="userName"></span>
                                    <svg class="w-4 h-4 text-slate-500 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                
                                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-56 rounded-2xl bg-white dark:bg-slate-900 shadow-xl border border-slate-100 dark:border-slate-800 py-2 z-50">
                                    <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-800">
                                        <p class="text-xs text-slate-400">Masuk sebagai</p>
                                        <p class="text-sm font-semibold truncate" x-text="userEmail"></p>
                                    </div>
                                    <template x-if="isAdmin">
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-emerald-600 dark:text-emerald-400 font-bold hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
                                            Dashboard Admin
                                        </a>
                                    </template>
                                    <a :href="isAdmin ? '{{ route('admin.profile') }}' : '{{ route('profile.settings') }}'" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        Pengaturan Profil
                                    </a>
                                    <template x-if="!isAdmin">
                                        <a href="{{ route('profile.history') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            Riwayat Pembelian
                                        </a>
                                    </template>

                                    <hr class="border-slate-100 dark:border-slate-800 my-1">
                                    <button @click="logout" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 text-left transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Log Out
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden">
                        <button @click="openMenu = !openMenu" class="p-2 text-slate-500 hover:text-slate-800 dark:hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="openMenu" @click.away="openMenu = false" x-transition class="md:hidden bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800 px-4 pt-2 pb-4 space-y-2">
                <a href="{{ route('home') }}" @click="openMenu = false" class="block px-3 py-2 text-base font-medium hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg">Beranda</a>
                <a href="{{ route('about') }}" @click="openMenu = false" class="block px-3 py-2 text-base font-medium hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg">Profil Wisata</a>
                <a href="{{ route('facilities') }}" @click="openMenu = false" class="block px-3 py-2 text-base font-medium hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg">Fasilitas</a>
                <a href="{{ route('tickets.index') }}" @click="openMenu = false" class="block px-3 py-2 text-base font-medium hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg">Tiket</a>
                <a href="{{ route('articles.index') }}" @click="openMenu = false" class="block px-3 py-2 text-base font-medium hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg">Artikel</a>
                
                <hr class="border-slate-100 dark:border-slate-800 my-2">
                
                <template x-if="!isLoggedIn">
                    <div class="grid grid-cols-2 gap-2 pt-2">
                        <a href="{{ route('login') }}" @click="openMenu = false" class="text-center font-semibold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 py-2.5 rounded-xl">Masuk</a>
                        <a href="{{ route('register') }}" @click="openMenu = false" class="text-center font-semibold bg-emerald-600 text-white py-2.5 rounded-xl">Daftar</a>
                    </div>
                </template>
                
                <template x-if="isLoggedIn">
                    <div class="space-y-1">
                        <template x-if="isAdmin">
                            <a href="{{ route('admin.dashboard') }}" @click="openMenu = false" class="block px-3 py-2 text-base font-bold text-emerald-600 dark:text-emerald-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg">Dashboard Admin</a>
                        </template>
                        <a :href="isAdmin ? '{{ route('admin.profile') }}' : '{{ route('profile.settings') }}'" @click="openMenu = false" class="block px-3 py-2 text-base font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg">Pengaturan Profil</a>
                        <template x-if="!isAdmin">
                            <a href="{{ route('profile.history') }}" @click="openMenu = false" class="block px-3 py-2 text-base font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg">Riwayat Pembelian</a>
                        </template>

                        <button @click="logout(); openMenu = false" class="w-full text-left block px-3 py-2 text-base font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 rounded-lg">Log Out</button>
                    </div>
                </template>
            </div>
        </header>

        <!-- Main Content Slot -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-slate-900 text-slate-400 border-t border-slate-800 pt-16 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                    
                    <!-- Brand Section -->
                    <div class="space-y-4">
                        <h3 class="text-white text-lg font-bold">Sawah Pulo Farm</h3>
                        <p class="text-sm">{{ \App\Models\SiteSetting::getValue('footer_description', 'Destinasi wisata alam pedesaan yang menyajikan keindahan alam persawahan dengan berbagai fasilitas menarik, nyaman, dan edukatif.') }}</p>
                    </div>

                    <!-- Quick Links -->
                    <div class="space-y-4">
                        <h4 class="text-white font-semibold">Tautan Cepat</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">Profil Wisata</a></li>
                            <li><a href="{{ route('facilities') }}" class="hover:text-white transition-colors">Fasilitas & Denah</a></li>
                            <li><a href="{{ route('tickets.index') }}" class="hover:text-white transition-colors">Tiket Wisata</a></li>
                            <li><a href="{{ route('articles.index') }}" class="hover:text-white transition-colors">Artikel & Berita</a></li>
                        </ul>
                    </div>

                    <!-- Operational & Maps -->
                    <div class="space-y-4">
                        <h4 class="text-white font-semibold">Informasi Wisata</h4>
                        <div class="text-sm space-y-1">
                            <p class="text-white">Jam Buka:</p>
                            <p>{{ \App\Models\SiteSetting::getValue('operating_days', 'Senin - Minggu') }}, {{ \App\Models\SiteSetting::getValue('operating_hours', '08:00 - 17:00 WIB') }}</p>
                            <p class="text-white mt-3">Alamat:</p>
                            <p>{{ \App\Models\SiteSetting::getValue('contact_address', 'Dusun Pulo, Kec. Sawah Indah, Kab. Mojokerto, Jawa Timur, Indonesia') }}</p>
                        </div>
                        <a href="{{ \App\Models\SiteSetting::getValue('contact_maps_url', 'https://maps.google.com') }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-emerald-400 hover:text-emerald-300 font-semibold transition-colors mt-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Petunjuk Arah Google Maps
                        </a>
                    </div>

                    <!-- Social Media & Contact -->
                    <div class="space-y-4">
                        <h4 class="text-white font-semibold">Hubungi Kami</h4>
                        <div class="flex gap-4">
                            <!-- Instagram -->
                            <a href="{{ \App\Models\SiteSetting::getValue('contact_instagram', 'https://instagram.com/sawahpulohub') }}" target="_blank" class="p-2 bg-slate-800 text-slate-400 hover:bg-emerald-600 hover:text-white rounded-xl transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            </a>
                            <!-- TikTok -->
                            <a href="{{ \App\Models\SiteSetting::getValue('contact_tiktok', 'https://tiktok.com/@sawahpulohub') }}" target="_blank" class="p-2 bg-slate-800 text-slate-400 hover:bg-emerald-600 hover:text-white rounded-xl transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.02 1.59 4.23.95.84 2.19 1.4 3.48 1.47V9.7c-1.37-.03-2.68-.48-3.79-1.28-.43-.3-.82-.67-1.14-1.09-.08 2.54-.06 5.09-.07 7.63-.03 1.87-.53 3.73-1.48 5.29-1.29 2.06-3.56 3.47-5.96 3.73-2.91.4-5.92-.93-7.53-3.41A9.01 9.01 0 011 15.34a9.08 9.08 0 015.42-8.31c.88-.36 1.83-.54 2.78-.54V10.4c-.72 0-1.44.14-2.11.43a5.1 5.1 0 00-3.07 4.14c-.2 1.63.4 3.29 1.62 4.35 1.07.95 2.55 1.39 3.96 1.18 1.55-.17 2.95-1.12 3.65-2.52.55-1.02.73-2.22.7-3.37-.02-4.82-.01-9.65-.01-14.47-.03-.04-.07-.08-.12-.12z"/></svg>
                            </a>
                            <!-- Facebook -->
                            <a href="https://facebook.com/sawahpulohub" target="_blank" class="p-2 bg-slate-800 text-slate-400 hover:bg-emerald-600 hover:text-white rounded-xl transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"/></svg>
                            </a>
                        </div>
                        
                        <!-- WhatsApp Link directly to chat room -->
                        <a href="https://wa.me/{{ \App\Models\SiteSetting::getValue('contact_whatsapp', '6281234567890') }}?text=Halo%20Sawah%20Pulo%20Hub,%20saya%20ingin%20bertanya..." target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600/10 hover:bg-emerald-600/20 text-emerald-400 font-semibold rounded-xl text-sm transition-all duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 11.968.01c3.184.001 6.177 1.242 8.426 3.496a11.883 11.883 0 0 1 3.493 8.428c-.005 6.56-5.34 11.897-11.912 11.897-2.005-.001-3.973-.508-5.727-1.472L0 24zm6.59-4.846c1.657.983 3.284 1.517 5.372 1.518 5.392 0 9.778-4.383 9.782-9.774a9.729 9.729 0 0 0-2.863-6.915 9.73 9.73 0 0 0-6.924-2.865c-5.399 0-9.786 4.384-9.79 9.778a9.697 9.697 0 0 0 1.514 5.25L2.24 19.86l4.407-1.564zM17.91 14.54c-.336-.168-1.99-1.013-2.3-1.124-.31-.113-.536-.169-.76.168-.224.338-.868 1.125-1.064 1.35-.197.225-.394.253-.73.084a9.348 9.348 0 0 1-2.71-1.675 10.287 10.287 0 0 1-1.876-2.333c-.197-.336-.021-.518.147-.686.152-.152.336-.394.505-.59.169-.197.225-.337.337-.563.112-.224.056-.421-.028-.59-.084-.168-.76-1.884-1.04-2.585-.273-.67-.552-.578-.76-.588-.21-.01-.452-.012-.693-.012a1.328 1.328 0 0 0-.964.45c-.336.338-1.285 1.266-1.285 3.093s1.324 3.596 1.506 3.848c.182.253 2.607 4.022 6.326 5.485.885.348 1.575.556 2.113.727.889.282 1.698.242 2.338.146.713-.107 1.99-.815 2.27-1.603.28-.787.28-1.462.196-1.603-.084-.14-.308-.225-.644-.393z"/></svg>
                            Hubungi via WhatsApp
                        </a>
                    </div>
                </div>

                <hr class="border-slate-800 my-8">

                <!-- Copy -->
                <div class="flex flex-col sm:flex-row justify-between items-center text-xs">
                    <p>&copy; 2026 Sawah Pulo Farm. Hak Cipta Dilindungi.</p>
                </div>
            </div>
        </footer>

        <!-- Navigation Controller Script -->
        <script>
            function navController() {
                return {
                    isLoggedIn: false,
                    isAdmin: false,
                    userName: '',
                    userEmail: '',
                    userInitials: '',
                    openMenu: false,
                    init() {
                        const token = localStorage.getItem('access_token');
                        const profileStr = localStorage.getItem('user_profile');
                        
                        if (token && profileStr) {
                            this.isLoggedIn = true;
                            const profile = JSON.parse(profileStr);
                            this.userName = profile.name;
                            this.userEmail = profile.email;
                            
                            const roles = profile.roles || [];
                            this.isAdmin = roles.some(role => role === 'admin' || (role && role.slug === 'admin'));
                            
                            // Get initials
                            const names = profile.name.split(' ');
                            this.userInitials = names.map(n => n[0]).slice(0, 2).join('').toUpperCase();
                        }
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
