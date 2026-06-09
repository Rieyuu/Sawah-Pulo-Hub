<x-tourist-layout>
    <x-slot name="title">Riwayat Pembelian | Sawah Pulo Hub</x-slot>

    <div class="max-w-4xl mx-auto px-4 py-12" x-data="bookingHistory()" x-init="initData()">
        
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white mb-8">Riwayat Pembelian Tiket</h1>

        <!-- Flash Success message -->
        <div x-show="successMessage" x-transition class="mb-6 p-4 text-sm text-green-800 rounded-2xl bg-green-50 dark:bg-slate-900 dark:text-green-400 border border-green-100 dark:border-green-950 flex justify-between items-center" role="alert" x-cloak>
            <div>
                <span class="font-bold">Sukses!</span> <span x-text="successMessage"></span>
            </div>
            <button @click="successMessage = ''" class="hover:opacity-75 font-bold">&times;</button>
        </div>

        <!-- Tabs filter -->
        <div class="flex border-b border-slate-200 dark:border-slate-800 gap-2 sm:gap-4 overflow-x-auto pb-px mb-8">
            <button @click="filterStatus('all')" class="py-3 px-4 text-xs sm:text-sm font-bold border-b-2 transition-all" :class="activeFilter === 'all' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-400 hover:text-slate-200'">
                Semua
            </button>
            <button @click="filterStatus('pending_payment')" class="py-3 px-4 text-xs sm:text-sm font-bold border-b-2 transition-all" :class="activeFilter === 'pending_payment' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-400 hover:text-slate-200'">
                Belum Bayar
            </button>
            <button @click="filterStatus('pending')" class="py-3 px-4 text-xs sm:text-sm font-bold border-b-2 transition-all" :class="activeFilter === 'pending' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-400 hover:text-slate-200'">
                Diproses
            </button>
            <button @click="filterStatus('success')" class="py-3 px-4 text-xs sm:text-sm font-bold border-b-2 transition-all" :class="activeFilter === 'success' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-400 hover:text-slate-200'">
                Sukses
            </button>
            <button @click="filterStatus('failed')" class="py-3 px-4 text-xs sm:text-sm font-bold border-b-2 transition-all" :class="activeFilter === 'failed' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-400 hover:text-slate-200'">
                Gagal
            </button>
        </div>

        <!-- Loading -->
        <div x-show="loading" class="flex flex-col items-center justify-center py-20 space-y-4">
            <div class="w-12 h-12 border-4 border-emerald-600 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-sm text-slate-500">Memuat riwayat transaksi...</p>
        </div>

        <!-- Empty State -->
        <div x-show="!loading && filteredOrders.length === 0" class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-16 text-center space-y-4" x-cloak>
            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto text-slate-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Tidak Ada Transaksi</h3>
            <p class="text-sm text-slate-500 max-w-sm mx-auto">Anda tidak memiliki pesanan tiket dalam kategori ini.</p>
        </div>

        <!-- Orders List -->
        <div x-show="!loading && filteredOrders.length > 0" class="space-y-4" x-cloak>
            <template x-for="order in filteredOrders" :key="order.id">
                <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 transition-all hover:border-slate-200 dark:hover:border-slate-700">
                    
                    <!-- Order Details -->
                    <div class="space-y-2 flex-grow">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-mono font-bold text-slate-400" x-text="order.ticket_code"></span>
                            <!-- Status Badge -->
                            <span class="text-[10px] px-2 py-0.5 font-bold rounded-full" :class="getStatusClass(order.status)" x-text="getStatusLabel(order.status)"></span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white" x-text="order.ticket.title"></h3>
                        
                        <div class="flex flex-wrap items-center gap-x-6 gap-y-1 text-xs text-slate-400">
                            <p>Jumlah: <span class="font-bold text-slate-700 dark:text-slate-300" x-text="order.quantity"></span> Orang</p>
                            <p>Total Harga: <span class="font-bold text-emerald-600 dark:text-emerald-400">Rp <span x-text="formatNumber(order.total_price)"></span></span></p>
                            <p>Tanggal Pesan: <span x-text="formatDate(order.created_at)"></span></p>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="flex-shrink-0 sm:text-right">
                        <!-- Pending Payment -> Upload CTA -->
                        <template x-if="order.status === 'pending_payment'">
                            <a :href="`/tickets/payment/${order.id}`" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-emerald-500/10">
                                Upload Bukti Bayar
                                &rarr;
                            </a>
                        </template>

                        <!-- Success -> Print Ticket CTA -->
                        <template x-if="order.status === 'success'">
                            <a :href="`/tickets/print/${order.id}`" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs font-bold rounded-xl transition-all">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                Cetak E-Tiket
                            </a>
                        </template>

                        <!-- Pending -> Waiting statement -->
                        <template x-if="order.status === 'pending'">
                            <span class="text-xs text-slate-400 italic font-semibold">Menunggu Verifikasi Admin</span>
                        </template>

                        <!-- Failed -> Rejected statement -->
                        <template x-if="order.status === 'failed'">
                            <span class="text-xs text-red-500 font-semibold">Pembayaran Ditolak</span>
                        </template>
                    </div>

                </div>
            </template>
        </div>

    </div>

    <script>
        function bookingHistory() {
            return {
                orders: [],
                filteredOrders: [],
                loading: true,
                activeFilter: 'all',
                successMessage: '',
                initData() {
                    this.checkAuth();
                    this.fetchOrders();
                    
                    // Check flash messages
                    const flash = sessionStorage.getItem('order_success_msg');
                    if (flash) {
                        this.successMessage = flash;
                        sessionStorage.removeItem('order_success_msg');
                    }
                },
                checkAuth() {
                    const token = localStorage.getItem('access_token');
                    if (!token) {
                        window.location.href = '/login';
                    }
                },
                fetchOrders() {
                    this.loading = true;
                    const token = localStorage.getItem('access_token');

                    axios.get('/api/orders/history', {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(res => {
                        this.orders = res.data.data;
                        this.applyFilter();
                        this.loading = false;
                    })
                    .catch(err => {
                        this.loading = false;
                        alert('Gagal mengambil data riwayat pembelian.');
                    });
                },
                filterStatus(status) {
                    this.activeFilter = status;
                    this.applyFilter();
                },
                applyFilter() {
                    if (this.activeFilter === 'all') {
                        this.filteredOrders = this.orders;
                    } else {
                        this.filteredOrders = this.orders.filter(order => order.status === this.activeFilter);
                    }
                },
                getStatusLabel(status) {
                    switch (status) {
                        case 'pending_payment': return 'Belum Bayar';
                        case 'pending': return 'Diproses';
                        case 'success': return 'Sukses';
                        case 'failed': return 'Gagal';
                        default: return status;
                    }
                },
                getStatusClass(status) {
                    switch (status) {
                        case 'pending_payment': return 'bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-300';
                        case 'pending': return 'bg-sky-100 text-sky-800 dark:bg-sky-950/40 dark:text-sky-300';
                        case 'success': return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300';
                        case 'failed': return 'bg-red-100 text-red-800 dark:bg-red-950/40 dark:text-red-300';
                        default: return 'bg-slate-100 text-slate-800';
                    }
                },
                formatNumber(val) {
                    return new Intl.NumberFormat('id-ID').format(val);
                },
                formatDate(dateStr) {
                    if (!dateStr) return '';
                    const d = new Date(dateStr);
                    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                }
            }
        }
    </script>
</x-tourist-layout>
