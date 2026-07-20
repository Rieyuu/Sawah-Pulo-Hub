<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tiket Resmi Sawah Pulo Farm</title>
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        @media print {
            body {
                background-color: #ffffff;
                color: #000000;
            }
            .no-print {
                display: none !important;
            }
            .print-card {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-8" x-data="eTicketPrinter()" x-init="fetchOrder()">

    <!-- Main Container -->
    <div class="max-w-md w-full bg-white border border-emerald-100 rounded-3xl p-6 sm:p-8 shadow-xl space-y-6 print-card relative" x-show="order" x-cloak>
        
        <!-- Header -->
        <div class="text-center border-b border-dashed border-emerald-100 pb-6 space-y-2">
            <h2 class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-emerald-600 to-teal-500 bg-clip-text text-transparent">
                SAWAH PULO FARM
            </h2>
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-widest">E-Tiket Masuk Resmi</p>
        </div>

        <!-- QR Code -->
        <div class="flex flex-col items-center justify-center py-4 space-y-4">
            <div class="p-3 bg-white border border-emerald-100 rounded-2xl shadow-inner">
                <img :src="`https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${order ? order.ticket_code : ''}`" alt="Ticket QR Code" class="w-36 h-36" />
            </div>
            <div class="text-center space-y-1">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Kode Tiket Unik</p>
                <h3 class="text-lg font-mono font-black text-slate-800 tracking-widest" x-text="order ? order.ticket_code : ''"></h3>
            </div>
        </div>

        <!-- Order Information Table -->
        <div class="border-t border-b border-dashed border-emerald-100 py-4 space-y-3 text-xs">
            <div class="flex justify-between">
                <span class="text-slate-400">Pengunjung</span>
                <span class="font-bold text-slate-800" x-text="order ? order.user.name : ''"></span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">No. WhatsApp</span>
                <span class="font-semibold text-slate-800" x-text="order ? order.user.whatsapp : ''"></span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Tiket Wisata</span>
                <span class="font-bold text-slate-800" x-text="order ? order.ticket.title : ''"></span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Jumlah Orang</span>
                <span class="font-bold text-slate-800"><span x-text="order ? order.quantity : 0"></span> Orang</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Total Harga</span>
                <span class="font-black text-emerald-600">Rp <span x-text="order ? formatNumber(order.total_price) : ''"></span></span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Tanggal Kunjungan</span>
                <span class="font-semibold text-slate-700" x-text="order ? formatDate(order.updated_at) : ''"></span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Berlaku Sampai</span>
                <span class="font-bold text-red-600" x-text="order ? formatDate(order.expired_at) : ''"></span>
            </div>
        </div>

        <!-- Footer / Instructions -->
        <div class="text-center space-y-2 text-[10px] text-slate-400 leading-relaxed">
            <p class="font-bold text-slate-600 font-medium dark:text-slate-300">PENTING & WAJIB DIKETAHUI:</p>
            <p>1. Tunjukkan QR Code di atas kepada petugas loket pintu masuk untuk discan.</p>
            <p>2. Jika QR Code gagal discan, berikan Kode Tiket Unik di atas kepada petugas.</p>
            <p>3. Tiket hanya dapat discan/digunakan 1 kali masuk.</p>
        </div>

        <!-- Print Action Button -->
        <div class="pt-4 no-print flex gap-2">
            <button @click="window.close()" class="w-1/3 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-2xl transition-all">
                Tutup Halaman
            </button>
            <button @click="window.print()" class="w-2/3 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-2xl shadow-lg shadow-emerald-500/10 transition-all flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak E-Tiket PDF
            </button>
        </div>
    </div>

    <!-- Loading -->
    <div x-show="!order" class="flex flex-col items-center justify-center space-y-4 no-print">
        <div class="w-12 h-12 border-4 border-emerald-600 border-t-transparent rounded-full animate-spin"></div>
        <p class="text-sm text-slate-600 font-medium dark:text-slate-300">Memproses E-Tiket Anda...</p>
    </div>

    <script>
        function eTicketPrinter() {
            return {
                orderId: '{{ $orderId }}',
                order: null,
                fetchOrder() {
                    const token = localStorage.getItem('access_token');
                    
                    axios.get(`/api/orders/${this.orderId}`, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(res => {
                        this.order = res.data.data;
                        
                        // Proteksi: Hanya e-ticket dengan status success yang bisa di-print
                        if (this.order.status !== 'success') {
                            alert('Tiket belum lunas atau pembayaran ditolak.');
                            window.location.href = '/profile/history';
                        }
                    })
                    .catch(err => {
                        alert('Gagal mengambil data tiket.');
                        window.location.href = '/profile/history';
                    });
                },
                formatNumber(val) {
                    return new Intl.NumberFormat('id-ID').format(val);
                },
                formatDate(dateStr) {
                    if (!dateStr) return '';
                    const d = new Date(dateStr);
                    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                }
            }
        }
    </script>
</body>
</html>
