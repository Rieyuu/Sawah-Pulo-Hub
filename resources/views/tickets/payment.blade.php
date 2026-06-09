<x-tourist-layout>
    <x-slot name="title">Pembayaran Tiket | Sawah Pulo Hub</x-slot>

    <div class="max-w-3xl mx-auto px-4 py-12" x-data="ticketPayment()" x-init="fetchOrder()">
        
        <!-- Header -->
        <div class="mb-8 space-y-2">
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Pembayaran Tiket</h1>
            <p class="text-sm text-slate-500">Silakan lakukan transfer pembayaran dan unggah bukti transfer di bawah ini.</p>
        </div>

        <!-- Alert -->
        <div x-show="errorMessage" x-transition class="mb-6 p-4 text-sm text-red-800 rounded-2xl bg-red-50 dark:bg-slate-900 dark:text-red-400 border border-red-100 dark:border-red-950" role="alert" x-cloak>
            <span class="font-medium">Gagal!</span> <span x-text="errorMessage"></span>
        </div>

        <!-- Loading -->
        <div x-show="loading" class="flex flex-col items-center justify-center py-20 space-y-4">
            <div class="w-12 h-12 border-4 border-emerald-600 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-sm text-slate-500">Memuat detail pesanan...</p>
        </div>

        <!-- Main Content -->
        <div x-show="!loading && order" class="grid grid-cols-1 md:grid-cols-3 gap-8" x-cloak>
            <!-- Bank & Upload Form (2 cols) -->
            <div class="md:col-span-2 space-y-6">
                <!-- Bank Info -->
                <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                    <h3 class="font-bold text-slate-900 dark:text-white">Tujuan Transfer Bank</h3>
                    
                    <div class="space-y-4">
                        <div class="p-5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 flex items-center justify-between">
                            <div class="space-y-1">
                                <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Bank Mandiri</span>
                                <h4 class="text-lg font-black text-slate-800 dark:text-slate-200">142-0017-8899-23</h4>
                                <p class="text-xs text-slate-400">a.n. BUMDes Wisata Sawah Pulo</p>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 bg-slate-200/50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-lg">Utama</span>
                        </div>
                    </div>

                    <div class="text-xs text-slate-400 leading-relaxed space-y-1">
                        <p class="font-semibold text-slate-500">Petunjuk Pembayaran:</p>
                        <p>1. Gunakan M-Banking / ATM untuk transfer ke rekening di atas.</p>
                        <p>2. Transfer tepat sesuai dengan total tagihan agar verifikasi berjalan cepat.</p>
                        <p>3. Simpan struk / tangkapan layar bukti transfer untuk diunggah di bawah.</p>
                    </div>
                </div>

                <!-- Upload Form -->
                <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                    <h3 class="font-bold text-slate-900 dark:text-white">Unggah Bukti Transfer</h3>
                    
                    <form @submit.prevent="submitPayment()" class="space-y-4">
                        <div>
                            <input type="file" @change="handleFileUpload($event)" accept="image/jpeg,image/png,image/jpg" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 dark:file:bg-emerald-950/40 dark:file:text-emerald-300 file:cursor-pointer" />
                            <p class="text-[10px] text-slate-400 mt-2">Format yang diizinkan: JPG, JPEG, PNG (Maksimal 2MB).</p>
                            <p x-show="errors.proof_of_payment" x-text="errors.proof_of_payment[0]" class="mt-1 text-xs text-red-600"></p>
                        </div>

                        <!-- Image Preview -->
                        <template x-if="imagePreview">
                            <div class="mt-4 p-2 border border-slate-100 dark:border-slate-800 rounded-2xl overflow-hidden max-w-xs bg-slate-50 dark:bg-slate-950">
                                <img :src="imagePreview" alt="Payment Proof Preview" class="w-full h-auto rounded-xl object-contain" />
                            </div>
                        </template>

                        <button type="submit" :disabled="submitting || !imageFile" class="w-full py-4 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white font-bold rounded-2xl shadow-lg shadow-emerald-600/10 hover:shadow-emerald-600/20 transition-all duration-200">
                            <span x-show="submitting" class="inline-block animate-spin mr-1">&#9696;</span>
                            Unggah Bukti Transfer
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Details (1 col) -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 shadow-sm space-y-6">
                    <h3 class="font-bold text-slate-900 dark:text-white pb-3 border-b border-slate-100 dark:border-slate-800/80">Detail Pembelian</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Kode Pembelian</span>
                            <span class="font-mono font-bold text-slate-800 dark:text-slate-200" x-text="order.ticket_code"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Tiket</span>
                            <span class="font-semibold text-slate-700 dark:text-slate-300" x-text="order.ticket.title"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Jumlah Orang</span>
                            <span class="font-bold text-slate-700 dark:text-slate-300" x-text="order.quantity"></span>
                        </div>
                        <hr class="border-slate-100 dark:border-slate-800/80">
                        <div class="flex justify-between text-base">
                            <span class="font-bold text-slate-800 dark:text-slate-200">Total Pembayaran</span>
                            <span class="font-black text-emerald-600 dark:text-emerald-400">Rp <span x-text="formatNumber(order.total_price)"></span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function ticketPayment() {
            return {
                orderId: '{{ $orderId }}',
                order: null,
                loading: true,
                submitting: false,
                errorMessage: '',
                imageFile: null,
                imagePreview: null,
                errors: {},
                fetchOrder() {
                    const token = localStorage.getItem('access_token');
                    
                    axios.get(`/api/orders/${this.orderId}`, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(res => {
                        this.order = res.data.data;
                        this.loading = false;
                        
                        // Proteksi jika status order sudah bukan pending_payment
                        if (this.order.status !== 'pending_payment') {
                            window.location.href = '/profile/history';
                        }
                    })
                    .catch(err => {
                        this.loading = false;
                        this.errorMessage = 'Gagal memuat detail pesanan.';
                    });
                },
                handleFileUpload(e) {
                    if (e.target.files.length > 0) {
                        const file = e.target.files[0];
                        this.imageFile = file;
                        
                        // Preview
                        const reader = new FileReader();
                        reader.onload = (event) => {
                            this.imagePreview = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },
                submitPayment() {
                    this.submitting = true;
                    this.errors = {};
                    this.errorMessage = '';
                    const token = localStorage.getItem('access_token');

                    const formData = new FormData();
                    formData.append('proof_of_payment', this.imageFile);

                    axios.post(`/api/orders/${this.orderId}/upload-payment`, formData, {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(res => {
                        this.submitting = false;
                        if (res.data.status === 200) {
                            sessionStorage.setItem('order_success_msg', 'Bukti pembayaran berhasil diunggah! Status pesanan kini menunggu verifikasi admin.');
                            window.location.href = '/profile/history';
                        }
                    })
                    .catch(err => {
                        this.submitting = false;
                        if (err.response && err.response.status === 422) {
                            this.errors = err.response.data.errors;
                        } else if (err.response && err.response.data && err.response.data.message) {
                            this.errorMessage = err.response.data.message;
                        } else {
                            this.errorMessage = 'Gagal mengunggah bukti pembayaran.';
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
