<x-admin-layout activeRoute="dashboard" header="Ringkasan Dashboard">
    <div class="space-y-8 animate-fade-in" x-data="adminDashboardController()" x-init="fetchStats()">
        
        <!-- Welcome Card -->
        <div class="relative bg-gradient-to-r from-emerald-600 to-teal-500 rounded-3xl p-6 sm:p-8 text-white overflow-hidden shadow-xl shadow-emerald-500/10">
            <!-- Decorative Background Graphic -->
            <div class="absolute right-0 bottom-0 top-0 opacity-10 pointer-events-none">
                <svg class="w-full h-full" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none"><polygon points="50,0 100,0 100,100 0,100"/></svg>
            </div>
            <div class="relative z-10 space-y-2 max-w-xl">
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Selamat Datang di Panel Admin!</h1>
                <p class="text-sm text-emerald-50/90 leading-relaxed">
                    Kelola data master tiket wisata, fasilitas edukasi, artikel blog, dan pengaturan profil kawasan wisata Sawah Pulo di sini secara langsung.
                </p>
            </div>
        </div>

        <!-- Dynamic Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <!-- Tickets Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 flex items-center justify-between shadow-sm">
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tiket Wisata</p>
                    <h3 class="text-3xl font-extrabold text-slate-800 dark:text-white" x-text="stats.ticketsCount">0</h3>
                </div>
                <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                </div>
            </div>

            <!-- Facilities Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 flex items-center justify-between shadow-sm">
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Fasilitas Edukasi</p>
                    <h3 class="text-3xl font-extrabold text-slate-800 dark:text-white" x-text="stats.facilitiesCount">0</h3>
                </div>
                <div class="w-12 h-12 bg-teal-50 dark:bg-teal-950/40 text-teal-600 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>

            <!-- Articles Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 flex items-center justify-between shadow-sm">
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Artikel & Berita</p>
                    <h3 class="text-3xl font-extrabold text-slate-800 dark:text-white" x-text="stats.articlesCount">0</h3>
                </div>
                <div class="w-12 h-12 bg-sky-50 dark:bg-sky-950/40 text-sky-600 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Overview Section -->
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 sm:p-8 space-y-6">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Aktivitas & Navigasi Cepat</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.tickets') }}" class="p-5 border border-slate-100 dark:border-slate-800 hover:border-emerald-500/30 rounded-2xl bg-slate-50/50 dark:bg-slate-950/50 hover:bg-emerald-50/10 dark:hover:bg-emerald-950/10 transition-all flex flex-col justify-between group">
                    <div class="space-y-2">
                        <span class="text-emerald-600 dark:text-emerald-400 font-bold block">Tiket Wisata</span>
                        <p class="text-xs text-slate-400">Atur harga, kapasitas/stok harian, status aktif, dan gambar visual tiket masuk wisata.</p>
                    </div>
                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 mt-4 inline-flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                        Kelola Tiket Wisata &rarr;
                    </span>
                </a>

                <a href="{{ route('admin.facilities') }}" class="p-5 border border-slate-100 dark:border-slate-800 hover:border-teal-500/30 rounded-2xl bg-slate-50/50 dark:bg-slate-950/50 hover:bg-teal-50/10 dark:hover:bg-teal-950/10 transition-all flex flex-col justify-between group">
                    <div class="space-y-2">
                        <span class="text-teal-600 dark:text-teal-400 font-bold block">Fasilitas Edukasi</span>
                        <p class="text-xs text-slate-400">Tambahkan sarana penunjang eduwisata seperti area pertanian hidroponik, gazebo, toilet, kantin, dll.</p>
                    </div>
                    <span class="text-xs font-semibold text-teal-600 dark:text-teal-400 mt-4 inline-flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                        Kelola Fasilitas &rarr;
                    </span>
                </a>

                <a href="{{ route('admin.articles') }}" class="p-5 border border-slate-100 dark:border-slate-800 hover:border-sky-500/30 rounded-2xl bg-slate-50/50 dark:bg-slate-950/50 hover:bg-sky-50/10 dark:hover:bg-sky-950/10 transition-all flex flex-col justify-between group">
                    <div class="space-y-2">
                        <span class="text-sky-600 dark:text-sky-400 font-bold block">Artikel Edukasi</span>
                        <p class="text-xs text-slate-400">Publikasikan tulisan edukasi perkebunan, peternakan, maupun pengumuman event terbaru di lokasi.</p>
                    </div>
                    <span class="text-xs font-semibold text-sky-600 dark:text-sky-400 mt-4 inline-flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                        Kelola Artikel &rarr;
                    </span>
                </a>

                <a href="{{ route('admin.settings') }}" class="p-5 border border-slate-100 dark:border-slate-800 hover:border-violet-500/30 rounded-2xl bg-slate-50/50 dark:bg-slate-950/50 hover:bg-violet-50/10 dark:hover:bg-violet-950/10 transition-all flex flex-col justify-between group">
                    <div class="space-y-2">
                        <span class="text-violet-600 dark:text-violet-400 font-bold block">Pengaturan Global</span>
                        <p class="text-xs text-slate-400">Perbarui Jam operasional wisata, kontak admin, tautan Google Maps, dan file denah 2D Site Plan.</p>
                    </div>
                    <span class="text-xs font-semibold text-violet-600 dark:text-violet-400 mt-4 inline-flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                        Konfigurasi Pengaturan &rarr;
                    </span>
                </a>
            </div>
        </div>

    </div>

    <script>
        function adminDashboardController() {
            return {
                stats: {
                    ticketsCount: 0,
                    facilitiesCount: 0,
                    articlesCount: 0
                },
                fetchStats() {
                    const token = localStorage.getItem('access_token');
                    
                    // Fetch real count from indexes
                    axios.get('/api/admin/tickets', {
                        headers: { 'Authorization': `Bearer ${token}` }
                    }).then(res => {
                        this.stats.ticketsCount = res.data.data.length;
                    });

                    axios.get('/api/admin/facilities', {
                        headers: { 'Authorization': `Bearer ${token}` }
                    }).then(res => {
                        this.stats.facilitiesCount = res.data.data.length;
                    });

                    axios.get('/api/admin/articles', {
                        headers: { 'Authorization': `Bearer ${token}` }
                    }).then(res => {
                        this.stats.articlesCount = res.data.data.length;
                    });
                }
            }
        }
    </script>
</x-admin-layout>
