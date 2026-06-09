<x-admin-layout activeRoute="orders" header="Verifikasi Pembayaran Tiket">
    <div class="space-y-6" x-data="adminOrdersController()" x-init="fetchOrders()">
        
        <!-- Tab Filters -->
        <div class="flex border-b border-slate-200 dark:border-slate-800 gap-4 overflow-x-auto pb-px">
            <button @click="filterStatus('all')" class="py-3 px-4 text-sm font-bold border-b-2 transition-all" :class="activeFilter === 'all' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-400 hover:text-slate-200'">
                Semua Pesanan
            </button>
            <button @click="filterStatus('pending')" class="py-3 px-4 text-sm font-bold border-b-2 transition-all flex items-center gap-2" :class="activeFilter === 'pending' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-400 hover:text-slate-200'">
                Menunggu Verifikasi
                <span class="px-2 py-0.5 text-2xs bg-amber-500 text-white font-bold rounded-full" x-show="pendingCount > 0" x-text="pendingCount">0</span>
            </button>
            <button @click="filterStatus('success')" class="py-3 px-4 text-sm font-bold border-b-2 transition-all" :class="activeFilter === 'success' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-400 hover:text-slate-200'">
                Sukses / Lunas
            </button>
            <button @click="filterStatus('failed')" class="py-3 px-4 text-sm font-bold border-b-2 transition-all" :class="activeFilter === 'failed' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-400 hover:text-slate-200'">
                Gagal / Ditolak
            </button>
        </div>

        <!-- Alert messages -->
        <div x-show="alert.show" x-transition class="p-4 rounded-2xl border text-sm flex justify-between items-center" :class="alert.type === 'success' ? 'bg-green-50 border-green-200 text-green-800 dark:bg-slate-900 dark:border-green-950 dark:text-green-400' : 'bg-red-50 border-red-200 text-red-800 dark:bg-slate-900 dark:border-red-950 dark:text-red-400'" role="alert" x-cloak>
            <span x-text="alert.message"></span>
            <button @click="alert.show = false" class="hover:opacity-70">&times;</button>
        </div>

        <!-- Loading Spinner -->
        <div x-show="loading" class="flex flex-col items-center justify-center py-20 space-y-4">
            <div class="w-12 h-12 border-4 border-emerald-600 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-sm text-slate-500">Memuat data transaksi...</p>
        </div>

        <!-- Empty State -->
        <div x-show="!loading && filteredOrders.length === 0" class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-16 text-center space-y-4" x-cloak>
            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800/60 rounded-2xl flex items-center justify-center mx-auto text-slate-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <h4 class="text-lg font-bold text-slate-700 dark:text-slate-300">Tidak Ada Pesanan Tiket</h4>
            <p class="text-sm text-slate-400 max-w-sm mx-auto">Tidak ada pesanan tiket wisata dalam kategori filter ini.</p>
        </div>

        <!-- Table View -->
        <div x-show="!loading && filteredOrders.length > 0" class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl overflow-hidden shadow-sm" x-cloak>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950 border-b border-slate-100 dark:border-slate-800/80 text-xs font-bold text-slate-400 uppercase tracking-wider">
                            <th class="px-6 py-4">Kode Order</th>
                            <th class="px-6 py-4">Wisatawan</th>
                            <th class="px-6 py-4">Tiket</th>
                            <th class="px-6 py-4">Qty</th>
                            <th class="px-6 py-4">Total Tagihan</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Tanggal Pesan</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        <template x-for="order in filteredOrders" :key="order.id">
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/40 transition-colors">
                                <td class="px-6 py-4 font-mono font-bold text-slate-500" x-text="order.ticket_code"></td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800 dark:text-slate-200" x-text="order.user.name"></div>
                                    <div class="text-[10px] text-slate-400" x-text="order.user.whatsapp"></div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300" x-text="order.ticket.title"></td>
                                <td class="px-6 py-4 font-bold text-slate-700 dark:text-slate-300" x-text="order.quantity"></td>
                                <td class="px-6 py-4 font-extrabold text-emerald-600 dark:text-emerald-400">Rp <span x-text="formatNumber(order.total_price)"></span></td>
                                <td class="px-6 py-4">
                                    <span class="text-[10px] px-2 py-0.5 font-bold rounded-full" :class="getStatusClass(order.status)" x-text="getStatusLabel(order.status)"></span>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-400" x-text="formatDate(order.created_at)"></td>
                                <td class="px-6 py-4 text-right">
                                    <template x-if="order.status === 'pending'">
                                        <button @click="openVerificationModal(order)" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-emerald-500/10">
                                            Verifikasi
                                        </button>
                                    </template>
                                    <template x-if="order.status !== 'pending'">
                                        <button @click="openDetailModal(order)" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-semibold rounded-xl transition-all">
                                            Detail
                                        </button>
                                    </template>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Verification / Detail Modal -->
        <div x-show="modal.open" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Backdrop -->
                <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="modal.open = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <!-- Modal Content -->
                <div x-show="modal.open" x-transition class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100 dark:border-slate-800">
                    <div class="p-6 sm:p-8 space-y-6" x-data="{ showFullProof: false }">
                        <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800/80 pb-4">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white" x-text="modal.order && modal.order.status === 'pending' ? 'Verifikasi Pembayaran Tiket' : 'Detail Transaksi Tiket'"></h3>
                            <button @click="modal.open = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white">&times;</button>
                        </div>

                        <!-- Modal Body -->
                        <template x-if="modal.order">
                            <div class="space-y-6">
                                <!-- Order Data -->
                                <div class="grid grid-cols-2 gap-4 text-xs">
                                    <div>
                                        <p class="text-slate-400">Kode Transaksi</p>
                                        <p class="font-mono font-bold text-sm text-slate-800 dark:text-slate-200" x-text="modal.order.ticket_code"></p>
                                    </div>
                                    <div>
                                        <p class="text-slate-400">Tanggal Pesan</p>
                                        <p class="font-semibold text-slate-700 dark:text-slate-300" x-text="formatDate(modal.order.created_at)"></p>
                                    </div>
                                    <div>
                                        <p class="text-slate-400">Nama Wisatawan</p>
                                        <p class="font-bold text-slate-800 dark:text-slate-200" x-text="modal.order.user.name"></p>
                                    </div>
                                    <div>
                                        <p class="text-slate-400">WhatsApp</p>
                                        <p class="font-semibold text-slate-700 dark:text-slate-300" x-text="modal.order.user.whatsapp"></p>
                                    </div>
                                    <div>
                                        <p class="text-slate-400">Tiket</p>
                                        <p class="font-bold text-slate-800 dark:text-slate-200" x-text="modal.order.ticket.title"></p>
                                    </div>
                                    <div>
                                        <p class="text-slate-400">Jumlah & Total Bayar</p>
                                        <p class="font-extrabold text-slate-800 dark:text-slate-200"><span x-text="modal.order.quantity"></span>x / <span class="text-emerald-600 dark:text-emerald-400">Rp <span x-text="formatNumber(modal.order.total_price)"></span></span></p>
                                    </div>
                                </div>

                                <!-- Proof of payment -->
                                <div class="border-t border-slate-100 dark:border-slate-800/80 pt-4 space-y-2">
                                    <p class="text-xs font-semibold text-slate-400">Bukti Transfer:</p>
                                    <template x-if="modal.order.proof_of_payment">
                                        <div class="relative max-w-xs border border-slate-100 dark:border-slate-800 rounded-2xl overflow-hidden bg-slate-50 dark:bg-slate-950 cursor-zoom-in">
                                            <img :src="modal.order.proof_of_payment" alt="Proof of payment" @click="showFullProof = true" class="w-full h-auto object-contain hover:opacity-90 transition-opacity" />
                                        </div>
                                    </template>
                                    <template x-if="!modal.order.proof_of_payment">
                                        <p class="text-xs text-slate-400 italic">Bukti transfer belum diunggah.</p>
                                    </template>

                                    <!-- Proof Full View Modal -->
                                    <div x-show="showFullProof" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80" x-cloak>
                                        <div class="relative max-w-md w-full bg-white dark:bg-slate-900 p-2 rounded-3xl" @click.away="showFullProof = false">
                                            <button @click="showFullProof = false" class="absolute -top-10 right-0 text-white font-bold text-lg">&times; Close</button>
                                            <img :src="modal.order.proof_of_payment" alt="Proof Full" class="w-full h-auto rounded-2xl max-h-[70vh] object-contain" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex justify-end gap-2 border-t border-slate-100 dark:border-slate-800/80 pt-4 mt-6">
                                    <button type="button" @click="modal.open = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl transition-all">
                                        Kembali
                                    </button>
                                    
                                    <template x-if="modal.order.status === 'pending'">
                                        <div class="flex gap-2">
                                            <button type="button" @click="rejectOrder(modal.order.id)" :disabled="modal.submitting" class="px-5 py-2.5 bg-red-600 hover:bg-red-500 disabled:opacity-50 text-white text-xs font-bold rounded-xl transition-all">
                                                Tolak Pembayaran
                                            </button>
                                            <button type="button" @click="approveOrder(modal.order.id)" :disabled="modal.submitting" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-emerald-500/10">
                                                Setujui Pembayaran
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function adminOrdersController() {
            return {
                orders: [],
                filteredOrders: [],
                loading: true,
                activeFilter: 'all',
                pendingCount: 0,
                alert: {
                    show: false,
                    type: 'success',
                    message: ''
                },
                modal: {
                    open: false,
                    submitting: false,
                    order: null
                },
                fetchOrders() {
                    this.loading = true;
                    const token = localStorage.getItem('access_token');

                    axios.get('/api/admin/orders', {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(res => {
                        this.orders = res.data.data;
                        this.pendingCount = this.orders.filter(o => o.status === 'pending').length;
                        this.applyFilter();
                        this.loading = false;
                    })
                    .catch(err => {
                        this.loading = false;
                        this.showAlert('danger', 'Gagal memuat daftar pesanan tiket.');
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
                        this.filteredOrders = this.orders.filter(o => o.status === this.activeFilter);
                    }
                },
                openVerificationModal(order) {
                    this.modal.order = order;
                    this.modal.submitting = false;
                    this.modal.open = true;
                },
                openDetailModal(order) {
                    this.modal.order = order;
                    this.modal.submitting = false;
                    this.modal.open = true;
                },
                approveOrder(id) {
                    if (!confirm('Apakah Anda yakin ingin menyetujui bukti pembayaran ini dan merilis e-ticket aktif?')) return;
                    this.modal.submitting = true;
                    const token = localStorage.getItem('access_token');

                    axios.post(`/api/admin/orders/${id}/approve`, {}, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(res => {
                        this.modal.submitting = false;
                        this.modal.open = false;
                        this.fetchOrders();
                        this.showAlert('success', 'Pembayaran berhasil disetujui. E-tiket diaktifkan.');
                    })
                    .catch(err => {
                        this.modal.submitting = false;
                        if (err.response && err.response.data && err.response.data.message) {
                            alert(err.response.data.message);
                        } else {
                            this.showAlert('danger', 'Gagal menyetujui pembayaran.');
                        }
                    });
                },
                rejectOrder(id) {
                    if (!confirm('Apakah Anda yakin ingin menolak pembayaran ini?')) return;
                    this.modal.submitting = true;
                    const token = localStorage.getItem('access_token');

                    axios.post(`/api/admin/orders/${id}/reject`, {}, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(res => {
                        this.modal.submitting = false;
                        this.modal.open = false;
                        this.fetchOrders();
                        this.showAlert('success', 'Pembayaran ditolak.');
                    })
                    .catch(err => {
                        this.modal.submitting = false;
                        this.showAlert('danger', 'Gagal menolak pembayaran.');
                    });
                },
                getStatusLabel(status) {
                    switch (status) {
                        case 'pending_payment': return 'Belum Bayar';
                        case 'pending': return 'Menunggu Verifikasi';
                        case 'success': return 'Sukses / Lunas';
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
                showAlert(type, message) {
                    this.alert.show = true;
                    this.alert.type = type;
                    this.alert.message = message;
                    setTimeout(() => {
                        this.alert.show = false;
                    }, 5000);
                },
                formatNumber(val) {
                    return new Intl.NumberFormat('id-ID').format(val);
                },
                formatDate(dateStr) {
                    if (!dateStr) return '';
                    const d = new Date(dateStr);
                    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                }
            }
        }
    </script>
</x-admin-layout>
