<x-tourist-layout>
    <x-slot name="title">Pemesanan Tiket | Sawah Pulo Hub</x-slot>

    <div class="max-w-3xl mx-auto px-4 py-12" x-data="ticketCheckout()" x-init="fetchTicket()">
        
        <!-- Header -->
        <div class="mb-8 space-y-2">
            <a href="/" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1">
                &larr; Kembali ke Beranda
            </a>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Pemesanan Tiket Wisata</h1>
        </div>

        <!-- Alert -->
        <div x-show="errorMessage" x-transition class="mb-6 p-4 text-sm text-red-800 rounded-2xl bg-red-50 dark:bg-slate-900 dark:text-red-400 border border-red-100 dark:border-red-950" role="alert" x-cloak>
            <span class="font-medium">Gagal!</span> <span x-text="errorMessage"></span>
        </div>

        <!-- Loading -->
        <div x-show="loading" class="flex flex-col items-center justify-center py-20 space-y-4">
            <div class="w-12 h-12 border-4 border-emerald-600 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-sm text-slate-500">Memuat detail tiket...</p>
        </div>

        <!-- Main Form -->
        <div x-show="!loading && ticket" class="grid grid-cols-1 md:grid-cols-3 gap-8" x-cloak>
            <!-- Ticket Info (2 cols) -->
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl overflow-hidden shadow-sm">
                    <div class="h-56 bg-slate-100 dark:bg-slate-800 relative">
                        <template x-if="ticket.image_path">
                            <img :src="ticket.image_path" alt="Ticket" class="w-full h-full object-cover" />
                        </template>
                        <template x-if="!ticket.image_path">
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        </template>
                    </div>
                    <div class="p-6 space-y-3">
                        <span class="text-[10px] px-2 py-0.5 font-bold rounded-full bg-emerald-100 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300">TIKET AKTIF</span>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white" x-text="ticket.title"></h2>
                        <p class="text-sm text-slate-500 leading-relaxed" x-text="ticket.description"></p>
                    </div>
                </div>

                <!-- Input Quantity -->
                <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 shadow-sm space-y-4">
                    <h3 class="font-bold text-slate-900 dark:text-white">Pilih Jumlah Tiket</h3>
                    
                    <div class="flex items-center gap-4">
                        <button type="button" @click="decrement()" class="w-11 h-11 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold rounded-xl flex items-center justify-center transition-colors">
                            -
                        </button>
                        <input type="number" x-model.number="quantity" min="1" :max="ticket.stock" class="w-20 text-center rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 font-bold text-lg focus:border-emerald-500 focus:ring-emerald-500" />
                        <button type="button" @click="increment()" class="w-11 h-11 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold rounded-xl flex items-center justify-center transition-colors">
                            +
                        </button>
                        <span class="text-xs text-slate-400">Sisa kuota hari ini: <b class="text-slate-600 dark:text-slate-300" x-text="ticket.stock"></b></span>
                    </div>
                    <p x-show="quantity > ticket.stock" class="text-xs text-red-600 font-medium">Jumlah tiket melebihi sisa kuota yang tersedia hari ini.</p>
                </div>
            </div>

            <!-- Order Summary (1 col) -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 shadow-sm space-y-6">
                    <h3 class="font-bold text-slate-900 dark:text-white pb-3 border-b border-slate-100 dark:border-slate-800/80">Ringkasan Pesanan</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Harga Tiket</span>
                            <span class="font-semibold text-slate-700 dark:text-slate-300">Rp <span x-text="formatNumber(ticket.price)"></span></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Jumlah Orang</span>
                            <span class="font-bold text-slate-700 dark:text-slate-300"><span x-text="quantity"></span>x</span>
                        </div>
                        <hr class="border-slate-100 dark:border-slate-800/80">
                        <div class="flex justify-between text-base">
                            <span class="font-bold text-slate-800 dark:text-slate-200">Total Tagihan</span>
                            <span class="font-black text-emerald-600 dark:text-emerald-400">Rp <span x-text="formatNumber(totalPrice)"></span></span>
                        </div>
                    </div>

                    <button @click="submitOrder()" :disabled="submitting || quantity < 1 || quantity > ticket.stock" class="w-full py-4 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white font-bold rounded-2xl shadow-lg shadow-emerald-600/10 hover:shadow-emerald-600/20 transition-all duration-200">
                        <span x-show="submitting" class="inline-block animate-spin mr-1">&#9696;</span>
                        Lanjut ke Pembayaran
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        function ticketCheckout() {
            return {
                ticketId: '{{ $ticketId }}',
                ticket: null,
                quantity: 1,
                loading: true,
                submitting: false,
                errorMessage: '',
                fetchTicket() {
                    const token = localStorage.getItem('access_token');
                    if (!token) {
                        localStorage.setItem('redirect_target', window.location.pathname);
                        window.location.href = '/login';
                        return;
                    }

                    axios.get(`/api/tickets/${this.ticketId}`, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(res => {
                        this.ticket = res.data.data;
                        this.loading = false;
                    })
                    .catch(err => {
                        this.loading = false;
                        this.errorMessage = 'Gagal memuat informasi tiket wisata.';
                    });
                },
                get totalPrice() {
                    return this.ticket ? this.ticket.price * this.quantity : 0;
                },
                increment() {
                    if (this.ticket && this.quantity < this.ticket.stock) {
                        this.quantity++;
                    }
                },
                decrement() {
                    if (this.quantity > 1) {
                        this.quantity--;
                    }
                },
                submitOrder() {
                    this.submitting = true;
                    this.errorMessage = '';
                    const token = localStorage.getItem('access_token');

                    axios.post('/api/orders', {
                        ticket_id: this.ticket.id,
                        quantity: this.quantity
                    }, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(res => {
                        this.submitting = false;
                        if (res.data.status === 201) {
                            // Redirect ke halaman upload pembayaran
                            const orderId = res.data.data.id;
                            window.location.href = `/tickets/payment/${orderId}`;
                        }
                    })
                    .catch(err => {
                        this.submitting = false;
                        if (err.response && err.response.data && err.response.data.message) {
                            this.errorMessage = err.response.data.message;
                        } else {
                            this.errorMessage = 'Terjadi kesalahan saat membuat pesanan.';
                        }
                    });
                },
                formatNumber(val) {
                    return new Intl.NumberFormat('id-ID').format(val);
                }
            }
        }
    </script>
</x-tourist-layout>
