<x-admin-layout activeRoute="dashboard" header="Ringkasan Dashboard">
    <div class="space-y-8 animate-fade-in" x-data="adminDashboardController()" x-init="initDashboard()">
        
        <!-- Peringatan Keamanan Kata Sandi Bawaan -->
        <div x-show="reports.is_using_default_password" class="p-4 rounded-2xl bg-red-500/10 border border-red-500/20 flex flex-col sm:flex-row items-center justify-between gap-4 text-red-850 dark:text-red-400" x-cloak>
            <div class="flex items-center gap-3">
                <span class="text-2xl animate-pulse">⚠️</span>
                <div class="text-left">
                    <h4 class="font-bold text-sm">Peringatan Keamanan Kata Sandi</h4>
                    <p class="text-[10px] text-slate-400 mt-0.5">Anda masih menggunakan kata sandi bawaan seeder. Demi keamanan data wisata, silakan segera ubah kata sandi Anda.</p>
                </div>
            </div>
            <a href="{{ route('admin.profile') }}" class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-red-500/10">
                Ubah Kata Sandi Sekarang
            </a>
        </div>

        <!-- Welcome Card -->
        <div class="relative bg-gradient-to-r from-emerald-600 to-teal-500 rounded-3xl p-6 sm:p-8 text-white overflow-hidden shadow-xl shadow-emerald-500/10">
            <div class="absolute right-0 bottom-0 top-0 opacity-10 pointer-events-none">
                <svg class="w-full h-full" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none"><polygon points="50,0 100,0 100,100 0,100"/></svg>
            </div>
            <div class="relative z-10 space-y-2 max-w-xl">
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Selamat Datang di Panel Admin!</h1>
                <p class="text-sm text-emerald-50/90 leading-relaxed">
                    Kelola data master tiket wisata, verifikasi pembayaran, pantau status scan tiket di pintu masuk, dan lihat statistik penjualan secara real-time.
                </p>
            </div>
        </div>

        <!-- Financial & Visitor Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <!-- Total Revenue Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 flex items-center justify-between shadow-sm">
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Pendapatan</p>
                    <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400">Rp <span x-text="formatNumber(reports.total_revenue)">0</span></h3>
                </div>
                <div class="w-12 h-12 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <!-- Tickets Sold Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 flex items-center justify-between shadow-sm">
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tiket Terjual</p>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white"><span x-text="reports.tickets_sold">0</span> <span class="text-xs font-medium text-slate-400">orang</span></h3>
                </div>
                <div class="w-12 h-12 bg-teal-500/10 text-teal-600 dark:text-teal-400 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                </div>
            </div>

            <!-- Total Unique Visitors Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 flex items-center justify-between shadow-sm">
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Wisatawan Unik</p>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white"><span x-text="reports.total_visitors">0</span> <span class="text-xs font-medium text-slate-400">akun</span></h3>
                </div>
                <div class="w-12 h-12 bg-sky-500/10 text-sky-600 dark:text-sky-400 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Middle Grid: Chart & Popular Tickets -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Side: Interactive SVG Sales Chart (2 Cols) -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Grafik Pendapatan (7 Hari Terakhir)</h3>
                        <p class="text-xs text-slate-400">Total penjualan harian tiket wisata yang berhasil lunas.</p>
                    </div>
                    <!-- Indicator -->
                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 py-1 px-3 rounded-full">Rupiah (Rp)</span>
                </div>

                <!-- Custom SVG-like Bar Chart using HTML and AlpineJS -->
                <div class="space-y-4">
                    <!-- Chart frame -->
                    <div class="h-64 bg-slate-50/50 dark:bg-slate-950/50 rounded-2xl p-4 border border-slate-100 dark:border-slate-800 flex items-end justify-between space-x-2 sm:space-x-4">
                        <template x-for="(day, idx) in reports.chart_data" :key="day.raw_date">
                            <div class="flex-1 flex flex-col items-center group relative h-full justify-end">
                                
                                <!-- Floating Tooltip -->
                                <div class="absolute bottom-full mb-3 bg-slate-900 text-white text-[10px] py-2 px-3 rounded-xl opacity-0 group-hover:opacity-100 transition-all duration-200 pointer-events-none z-10 whitespace-nowrap shadow-xl" style="transform: translateY(-5px);">
                                    <p class="font-bold border-b border-slate-800 pb-1 mb-1" x-text="day.raw_date"></p>
                                    <p>Pendapatan: <span class="font-bold text-emerald-400">Rp <span x-text="formatNumber(day.revenue)"></span></span></p>
                                    <p>Terjual: <span class="font-bold" x-text="day.tickets"></span> tiket</p>
                                </div>

                                <!-- Dynamic Bar -->
                                <div class="w-full bg-emerald-600 dark:bg-emerald-500/90 group-hover:bg-emerald-500 dark:group-hover:bg-emerald-400 rounded-t-xl transition-all duration-500 cursor-pointer relative" 
                                     :style="'height: ' + getBarHeight(day.revenue) + '%'">
                                </div>

                                <!-- Label (Date) -->
                                <span class="text-[10px] text-slate-400 mt-2 font-bold" x-text="day.label"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Right Side: Popular Tickets (1 Col) -->
            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Tiket Terpopuler</h3>
                    <p class="text-xs text-slate-400">Kuantitas penjualan lunas tertinggi.</p>
                </div>

                <div class="space-y-5">
                    <template x-if="reports.popular_tickets.length === 0">
                        <p class="text-xs text-slate-400 italic py-4">Belum ada transaksi tiket lunas.</p>
                    </template>

                    <template x-for="(ticket, idx) in reports.popular_tickets" :key="idx">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-xs font-semibold">
                                <span class="text-slate-700 dark:text-slate-300 truncate max-w-[180px]" x-text="ticket.title"></span>
                                <span class="text-slate-400" x-text="ticket.sold + ' Terjual'"></span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-full rounded-full transition-all duration-700"
                                     :style="'width: ' + getPopularPercentage(ticket.sold) + '%'"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Bottom Grid: Recent Orders & Master Data Counts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Side: Recent Orders (2 Cols) -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Transaksi Terbaru</h3>
                        <p class="text-xs text-slate-400">Daftar 5 aktivitas pembelian tiket terbaru.</p>
                    </div>
                    <a href="{{ route('admin.orders') }}" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 font-bold uppercase tracking-wider">
                                <th class="pb-3 pr-2">Kode</th>
                                <th class="pb-3">Wisatawan</th>
                                <th class="pb-3">Tiket</th>
                                <th class="pb-3 text-center">Jumlah</th>
                                <th class="pb-3 text-right">Total</th>
                                <th class="pb-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <template x-if="reports.recent_orders.length === 0">
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-slate-400 italic">Belum ada transaksi pembelian.</td>
                                </tr>
                            </template>

                            <template x-for="order in reports.recent_orders" :key="order.id">
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/20 transition-colors">
                                    <td class="py-3.5 pr-2 font-mono font-bold text-slate-900 dark:text-white" x-text="order.ticket_code"></td>
                                    <td class="py-3.5" x-text="order.user_name"></td>
                                    <td class="py-3.5 font-medium truncate max-w-[130px]" x-text="order.ticket_title"></td>
                                    <td class="py-3.5 text-center font-bold" x-text="order.quantity"></td>
                                    <td class="py-3.5 text-right font-black text-slate-800 dark:text-slate-200" x-text="'Rp ' + formatNumber(order.total_price)"></td>
                                    <td class="py-3.5 text-center">
                                        <span :class="getStatusBadgeClass(order.status)" class="px-2.5 py-1 rounded-full text-[10px] font-bold" x-text="getStatusLabel(order.status)"></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Side: Master Data Counts & Navigation -->
            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Ringkasan Data & Akses Cepat</h3>

                <div class="space-y-4">
                    <!-- Tickets Count & Link -->
                    <a href="{{ route('admin.tickets') }}" class="flex items-center justify-between p-3.5 border border-slate-100 dark:border-slate-800 rounded-2xl hover:border-emerald-500/20 bg-slate-50/50 dark:bg-slate-950/30 hover:bg-emerald-500/5 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-emerald-500/10 text-emerald-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800 dark:text-slate-200">Tiket Wisata</p>
                                <p class="text-[10px] text-slate-400" x-text="stats.ticketsCount + ' Tiket terdaftar'"></p>
                            </div>
                        </div>
                        <span class="text-slate-400 group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </a>

                    <!-- Facilities Count & Link -->
                    <a href="{{ route('admin.facilities') }}" class="flex items-center justify-between p-3.5 border border-slate-100 dark:border-slate-800 rounded-2xl hover:border-teal-500/20 bg-slate-50/50 dark:bg-slate-950/30 hover:bg-teal-500/5 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-teal-500/10 text-teal-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800 dark:text-slate-200">Fasilitas Edukasi</p>
                                <p class="text-[10px] text-slate-400" x-text="stats.facilitiesCount + ' Sarana terdaftar'"></p>
                            </div>
                        </div>
                        <span class="text-slate-400 group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </a>

                    <!-- Articles Count & Link -->
                    <a href="{{ route('admin.articles') }}" class="flex items-center justify-between p-3.5 border border-slate-100 dark:border-slate-800 rounded-2xl hover:border-sky-500/20 bg-slate-50/50 dark:bg-slate-950/30 hover:bg-sky-500/5 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-sky-500/10 text-sky-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800 dark:text-slate-200">Artikel Blog</p>
                                <p class="text-[10px] text-slate-400" x-text="stats.articlesCount + ' Artikel edukasi'"></p>
                            </div>
                        </div>
                        <span class="text-slate-400 group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </a>

                    <!-- Global Settings Link -->
                    <a href="{{ route('admin.settings') }}" class="flex items-center justify-between p-3.5 border border-slate-100 dark:border-slate-800 rounded-2xl hover:border-violet-500/20 bg-slate-50/50 dark:bg-slate-950/30 hover:bg-violet-500/5 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-violet-500/10 text-violet-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800 dark:text-slate-200">Pengaturan Global</p>
                                <p class="text-[10px] text-slate-400">Operasional & Informasi Wisata</p>
                            </div>
                        </div>
                        <span class="text-slate-400 group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </a>
                </div>
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
                reports: {
                    total_revenue: 0,
                    tickets_sold: 0,
                    total_visitors: 0,
                    chart_data: [],
                    popular_tickets: [],
                    recent_orders: []
                },

                initDashboard() {
                    this.fetchStats();
                    this.fetchReports();
                },

                fetchStats() {
                    const token = localStorage.getItem('access_token');
                    
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
                },

                fetchReports() {
                    const token = localStorage.getItem('access_token');
                    
                    axios.get('/api/admin/reports/dashboard', {
                        headers: { 'Authorization': `Bearer ${token}` }
                    }).then(res => {
                        this.reports = res.data.data;
                    }).catch(err => {
                        console.error("Gagal mengambil data laporan dashboard.", err);
                    });
                },

                getBarHeight(revenue) {
                    if (revenue === 0) return 4;
                    const maxRevenue = Math.max(...this.reports.chart_data.map(d => d.revenue));
                    if (maxRevenue === 0) return 4;
                    return Math.max((revenue / maxRevenue) * 90, 4); // Max 90%
                },

                getPopularPercentage(sold) {
                    if (sold === 0) return 0;
                    const maxSold = Math.max(...this.reports.popular_tickets.map(t => t.sold));
                    if (maxSold === 0) return 0;
                    return (sold / maxSold) * 100;
                },

                getStatusBadgeClass(status) {
                    switch (status) {
                        case 'success':
                            return 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400';
                        case 'pending':
                            return 'bg-blue-500/10 text-blue-600 dark:text-blue-400';
                        case 'pending_payment':
                            return 'bg-amber-500/10 text-amber-600 dark:text-amber-400';
                        case 'failed':
                            return 'bg-red-500/10 text-red-600 dark:text-red-400';
                        default:
                            return 'bg-slate-500/10 text-slate-600 dark:text-slate-400';
                    }
                },

                getStatusLabel(status) {
                    switch (status) {
                        case 'success':
                            return 'Sukses';
                        case 'pending':
                            return 'Diproses';
                        case 'pending_payment':
                            return 'Belum Bayar';
                        case 'failed':
                            return 'Gagal';
                        default:
                            return status;
                    }
                },

                formatNumber(val) {
                    return new Intl.NumberFormat('id-ID').format(val);
                }
            }
        }
    </script>
</x-admin-layout>
