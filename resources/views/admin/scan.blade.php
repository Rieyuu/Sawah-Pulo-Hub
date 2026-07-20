<x-admin-layout activeRoute="scan" header="Scan Tiket Masuk">
    <div class="space-y-8 animate-fade-in" x-data="adminScanController()" x-init="initScanner()">
        
        <!-- Header & Tab Toggle -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Pintu Masuk Loket</h1>
                <p class="text-sm text-slate-600 font-medium dark:text-slate-300">Scan QR Code tiket wisatawan secara real-time atau masukkan kode tiket secara manual.</p>
            </div>
            
            <!-- Mode Switcher Tabs -->
            <div class="inline-flex p-1 bg-slate-200/60 dark:bg-slate-900 rounded-2xl border border-emerald-100/20">
                <button @click="switchTab('camera')" :class="activeTab === 'camera' ? 'bg-white dark:bg-slate-800 text-slate-950 dark:text-white shadow-xl shadow-slate-200/60 dark:shadow-none hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-emerald-900/10 hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300' : 'text-slate-600 font-medium dark:text-slate-300 hover:text-slate-200'" class="px-4 py-2 text-xs font-bold rounded-xl transition-all">
                    📷 Kamera Scanner
                </button>
                <button @click="switchTab('manual')" :class="activeTab === 'manual' ? 'bg-white dark:bg-slate-800 text-slate-950 dark:text-white shadow-xl shadow-slate-200/60 dark:shadow-none hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-emerald-900/10 hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300' : 'text-slate-600 font-medium dark:text-slate-300 hover:text-slate-200'" class="px-4 py-2 text-xs font-bold rounded-xl transition-all">
                    ✍️ Input Manual
                </button>
            </div>
        </div>

        <!-- Main Display Panel -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Panel: Scanner View / Form Input (2 Columns) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Results Display Overlay (Active when result is present) -->
                <div x-show="scanResult" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="bg-white dark:bg-slate-900 border border-emerald-100 dark:border-emerald-900/40 rounded-3xl p-6 sm:p-8 shadow-xl shadow-slate-200/60 dark:shadow-none hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-emerald-900/10 hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300 space-y-6" x-cloak>
                    
                    <!-- SUCCESS CARD -->
                    <template x-if="scanResult && scanResult.success">
                        <div class="space-y-6">
                            <div class="flex flex-col items-center text-center p-6 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl space-y-3">
                                <div class="w-16 h-16 bg-emerald-500 text-white rounded-full flex items-center justify-center text-3xl shadow-lg shadow-emerald-500/30 animate-bounce">
                                    ✓
                                </div>
                                <h3 class="text-xl font-black text-emerald-600 dark:text-emerald-400">TIKET VALID!</h3>
                                <p class="text-xs text-slate-600 font-medium dark:text-slate-300" x-text="scanResult.message"></p>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-950 p-6 rounded-2xl border border-emerald-100 dark:border-emerald-900/30 space-y-4">
                                <h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm border-b border-emerald-100 dark:border-emerald-900/30 pb-2">Informasi Kunjungan</h4>
                                <div class="grid grid-cols-2 gap-4 text-xs">
                                    <div>
                                        <p class="text-slate-600 font-medium dark:text-slate-300">Nama Pengunjung</p>
                                        <p class="font-bold text-slate-800 dark:text-slate-200 mt-1" x-text="scanResult.data.user.name"></p>
                                    </div>
                                    <div>
                                        <p class="text-slate-600 font-medium dark:text-slate-300">WhatsApp</p>
                                        <p class="font-bold text-slate-800 dark:text-slate-200 mt-1" x-text="scanResult.data.user.whatsapp"></p>
                                    </div>
                                    <div>
                                        <p class="text-slate-600 font-medium dark:text-slate-300">Jenis Tiket</p>
                                        <p class="font-bold text-slate-800 dark:text-slate-200 mt-1" x-text="scanResult.data.ticket.title"></p>
                                    </div>
                                    <div>
                                        <p class="text-slate-600 font-medium dark:text-slate-300">Jumlah Orang</p>
                                        <p class="font-black text-emerald-600 dark:text-emerald-400 mt-1" x-text="scanResult.data.quantity + ' Orang'"></p>
                                    </div>
                                    <div>
                                        <p class="text-slate-600 font-medium dark:text-slate-300">Kode Tiket</p>
                                        <p class="font-mono font-bold text-slate-800 dark:text-slate-200 mt-1" x-text="scanResult.data.ticket_code"></p>
                                    </div>
                                    <div>
                                        <p class="text-slate-600 font-medium dark:text-slate-300">Waktu Digunakan</p>
                                        <p class="font-bold text-slate-800 dark:text-slate-200 mt-1" x-text="formatDate(scanResult.data.used_at)"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- FAILURE CARD -->
                    <template x-if="scanResult && !scanResult.success">
                        <div class="space-y-6">
                            <div class="flex flex-col items-center text-center p-6 bg-red-500/10 border border-red-500/20 rounded-2xl space-y-3">
                                <div class="w-16 h-16 bg-red-500 text-white rounded-full flex items-center justify-center text-3xl shadow-lg shadow-red-500/30 animate-pulse">
                                    ✕
                                </div>
                                <h3 class="text-xl font-black text-red-600 dark:text-red-400">TIKET TIDAK VALID</h3>
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-300" x-text="scanResult.message"></p>
                            </div>

                            <template x-if="scanResult.ticket_code">
                                <div class="bg-slate-50 dark:bg-slate-950 p-4 rounded-2xl border border-emerald-100 dark:border-emerald-900/30 flex justify-between items-center text-xs">
                                    <span class="text-slate-600 font-medium dark:text-slate-300">Kode Tiket yang Dipindai</span>
                                    <span class="font-mono font-bold text-slate-700 dark:text-slate-300" x-text="scanResult.ticket_code"></span>
                                </div>
                            </template>
                        </div>
                    </template>

                    <button @click="resetScan()" class="w-full py-4 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-2xl shadow-lg transition-all">
                        ← Pindai Tiket Berikutnya
                    </button>
                </div>

                <!-- CAMERA SCANNER VIEW -->
                <div x-show="activeTab === 'camera' && !scanResult" class="bg-white dark:bg-slate-900 border border-emerald-100 dark:border-emerald-900/40 rounded-3xl p-6 sm:p-8 shadow-xl shadow-slate-200/60 dark:shadow-none hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-emerald-900/10 hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-emerald-100 dark:border-emerald-900/40">
                        <h3 class="font-bold text-slate-900 dark:text-white">Pemindaian Kamera QR Code</h3>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-600 animate-pulse">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Aktif
                        </span>
                    </div>

                    <!-- Camera selection -->
                    <div class="space-y-2" x-show="cameras.length > 0">
                        <label class="text-xs font-semibold text-slate-600 font-medium dark:text-slate-300">Pilih Kamera Perangkat:</label>
                        <select x-model="selectedCameraId" @change="restartCamera()" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-emerald-100/60 dark:border-emerald-900/30 rounded-2xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <template x-for="camera in cameras" :key="camera.id">
                                <option :value="camera.id" x-text="camera.label"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Camera Window -->
                    <div class="relative w-full max-w-md mx-auto aspect-square overflow-hidden rounded-3xl border border-emerald-100 dark:border-emerald-900/30 bg-slate-950 flex items-center justify-center">
                        <div id="reader" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out"></div>
                        
                        <!-- Overlay Loading when verifying -->
                        <div x-show="isProcessing" class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm flex flex-col items-center justify-center space-y-4 text-white z-10" x-cloak>
                            <div class="w-12 h-12 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
                            <p class="text-xs font-semibold">Memverifikasi keabsahan tiket...</p>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="text-xs text-slate-600 font-medium dark:text-slate-300 text-center max-w-sm mx-auto leading-relaxed">
                        Posisikan QR Code e-tiket wisatawan tepat di dalam kotak kamera. Deteksi akan dilakukan secara instan.
                    </div>
                </div>

                <!-- MANUAL INPUT VIEW -->
                <div x-show="activeTab === 'manual' && !scanResult" class="bg-white dark:bg-slate-900 border border-emerald-100 dark:border-emerald-900/40 rounded-3xl p-6 sm:p-8 shadow-xl shadow-slate-200/60 dark:shadow-none hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-emerald-900/10 hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300 space-y-6" x-cloak>
                    <h3 class="font-bold text-slate-900 dark:text-white pb-3 border-b border-emerald-100 dark:border-emerald-900/40">Input Kode Tiket Manual</h3>

                    <form @submit.prevent="verifyManualCode()" class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-600 font-medium dark:text-slate-300 uppercase tracking-wider">Masukkan Kode Tiket</label>
                            <input type="text" x-model="manualCode" placeholder="Contoh: SWP-A7B8C9D0" uppercase autocomplete="off" required :disabled="isProcessing" class="w-full px-5 py-4 text-lg font-mono font-bold tracking-widest text-slate-800 dark:text-white bg-slate-50 dark:bg-slate-950 border border-emerald-100 dark:border-emerald-900/30 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all placeholder:text-slate-600 font-medium dark:text-slate-300 placeholder:font-sans placeholder:tracking-normal placeholder:text-sm" />
                        </div>

                        <button type="submit" :disabled="isProcessing || !manualCode" class="w-full py-4 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white font-bold rounded-2xl shadow-lg transition-all">
                            <span x-show="isProcessing" class="inline-block animate-spin mr-1">&#9696;</span>
                            Verifikasi Tiket
                        </button>
                    </form>
                </div>

            </div>

            <!-- Right Panel: Info & History (1 Column) -->
            <div class="space-y-6">
                <!-- Guide Card -->
                <div class="bg-white dark:bg-slate-900 border border-emerald-100 dark:border-emerald-900/40 rounded-3xl p-6 shadow-xl shadow-slate-200/60 dark:shadow-none hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-emerald-900/10 hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300 space-y-4">
                    <h3 class="font-bold text-slate-900 dark:text-white">Petunjuk Loket Masuk</h3>
                    <div class="text-xs text-slate-600 font-medium dark:text-slate-300 leading-relaxed space-y-2.5">
                        <p>1. Wisatawan menyodorkan QR Code dari e-tiket pada handphone atau lembar cetak.</p>
                        <p>2. Arahkan QR Code ke kamera scanner admin.</p>
                        <p>3. Jika pembacaan gagal, gunakan menu **Input Manual** dan ketikkan kode unik yang tertera di tiket.</p>
                        <p>4. Pastikan status berwarna hijau **TIKET VALID** sebelum mengizinkan wisatawan melintasi pintu masuk.</p>
                    </div>
                </div>

                <!-- Recent Scan logs (Client-side tracking) -->
                <div class="bg-white dark:bg-slate-900 border border-emerald-100 dark:border-emerald-900/40 rounded-3xl p-6 shadow-xl shadow-slate-200/60 dark:shadow-none hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-emerald-900/10 hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300 space-y-4">
                    <h3 class="font-bold text-slate-900 dark:text-white">Pindaian Terakhir</h3>
                    
                    <div class="space-y-3">
                        <template x-if="recentScans.length === 0">
                            <p class="text-xs text-slate-600 font-medium dark:text-slate-300 italic py-2">Belum ada aktivitas scan.</p>
                        </template>

                        <template x-for="scan in recentScans" :key="scan.time">
                            <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-emerald-100 dark:border-emerald-900/30 flex items-center justify-between text-xs">
                                <div class="space-y-0.5">
                                    <p class="font-mono font-bold text-slate-800 dark:text-slate-200" x-text="scan.code"></p>
                                    <p class="text-[10px] text-slate-600 font-medium dark:text-slate-300" x-text="scan.info"></p>
                                </div>
                                <span :class="scan.valid ? 'bg-emerald-500/10 text-emerald-600' : 'bg-red-500/10 text-red-600'" class="px-2.5 py-1 rounded-lg font-bold text-[10px]" x-text="scan.valid ? 'VALID' : 'GAGAL'">
                                </span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Include html5-qrcode CDN -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <!-- Scanner Script Logic -->
    <script>
        function adminScanController() {
            return {
                activeTab: 'camera',
                manualCode: '',
                isProcessing: false,
                scanResult: null,
                cameras: [],
                selectedCameraId: null,
                html5QrCode: null,
                recentScans: [],

                // Web Audio API Synthesizer
                audioCtx: null,

                initScanner() {
                    // Coba minta izin kamera dan list list kamera
                    Html5Qrcode.getCameras().then(devices => {
                        if (devices && devices.length > 0) {
                            this.cameras = devices;
                            this.selectedCameraId = devices[0].id;
                            
                            // Mulai scan jika tab active adalah camera
                            if (this.activeTab === 'camera') {
                                this.startCameraScanner();
                            }
                        }
                    }).catch(err => {
                        console.warn("Gagal mengakses daftar kamera perangkat.", err);
                    });
                },

                switchTab(tab) {
                    if (this.activeTab === tab) return;
                    this.activeTab = tab;
                    this.scanResult = null;

                    if (tab === 'camera') {
                        this.startCameraScanner();
                    } else {
                        this.stopCameraScanner();
                    }
                },

                startCameraScanner() {
                    this.stopCameraScanner().then(() => {
                        if (!this.selectedCameraId) return;

                        this.html5QrCode = new Html5Qrcode("reader");
                        this.html5QrCode.start(
                            this.selectedCameraId, 
                            {
                                fps: 15,
                                qrbox: { width: 250, height: 250 }
                            },
                            (decodedText, decodedResult) => {
                                // QR code terdeteksi!
                                if (!this.isProcessing && !this.scanResult) {
                                    this.verifyTicketCode(decodedText);
                                }
                            },
                            (errorMessage) => {
                                // Abaikan log error scan rutin untuk menjaga console bersih
                            }
                        ).catch(err => {
                            console.error("Gagal menjalankan kamera scanner.", err);
                        });
                    });
                },

                async stopCameraScanner() {
                    if (this.html5QrCode && this.html5QrCode.isScanning) {
                        try {
                            await this.html5QrCode.stop();
                        } catch (err) {
                            console.error("Error saat memberhentikan kamera.", err);
                        }
                        this.html5QrCode = null;
                    }
                },

                restartCamera() {
                    this.startCameraScanner();
                },

                verifyManualCode() {
                    if (!this.manualCode) return;
                    this.verifyTicketCode(this.manualCode.toUpperCase().trim());
                },

                verifyTicketCode(code) {
                    this.isProcessing = true;
                    this.stopCameraScanner(); // Stop scanning untuk memproses

                    const token = localStorage.getItem('access_token');
                    
                    axios.post('/api/admin/tickets/scan', {
                        ticket_code: code
                    }, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(res => {
                        this.isProcessing = false;
                        this.playBeepSuccess();
                        
                        this.scanResult = {
                            success: true,
                            message: res.data.message,
                            data: res.data.data
                        };

                        // Tambah ke riwayat pindaian terakhir
                        this.addRecentScan(code, `${res.data.data.quantity} org • ${res.data.data.ticket.title}`, true);
                    })
                    .catch(err => {
                        this.isProcessing = false;
                        this.playBeepFailure();

                        let message = 'Gagal memverifikasi tiket.';
                        if (err.response && err.response.data && err.response.data.message) {
                            message = err.response.data.message;
                        }

                        this.scanResult = {
                            success: false,
                            message: message,
                            ticket_code: code
                        };

                        this.addRecentScan(code, message, false);
                    });
                },

                addRecentScan(code, info, valid) {
                    this.recentScans.unshift({
                        code: code,
                        info: info,
                        valid: valid,
                        time: new Date().getTime()
                    });

                    // Batasi hanya 5 log teratas
                    if (this.recentScans.length > 5) {
                        this.recentScans.pop();
                    }
                },

                resetScan() {
                    this.scanResult = null;
                    this.manualCode = '';
                    
                    if (this.activeTab === 'camera') {
                        this.startCameraScanner();
                    }
                },

                formatDate(dateStr) {
                    if (!dateStr) return '-';
                    const date = new Date(dateStr);
                    const pad = (n) => String(n).padStart(2, '0');
                    return `${pad(date.getDate())}-${pad(date.getMonth() + 1)}-${date.getFullYear()} ${pad(date.getHours())}:${pad(date.getMinutes())}`;
                },

                // Audio Synthesis (Web Audio API)
                getAudioContext() {
                    if (!this.audioCtx) {
                        this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                    }
                    return this.audioCtx;
                },

                playBeepSuccess() {
                    try {
                        const ctx = this.getAudioContext();
                        
                        // Bunyi Beep 1
                        const osc1 = ctx.createOscillator();
                        const gain1 = ctx.createGain();
                        osc1.type = 'sine';
                        osc1.frequency.setValueAtTime(523.25, ctx.currentTime); // C5
                        gain1.gain.setValueAtTime(0.1, ctx.currentTime);
                        gain1.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.1);
                        osc1.connect(gain1);
                        gain1.connect(ctx.destination);
                        osc1.start();
                        osc1.stop(ctx.currentTime + 0.1);

                        // Bunyi Beep 2 (Lebih tinggi)
                        const osc2 = ctx.createOscillator();
                        const gain2 = ctx.createGain();
                        osc2.type = 'sine';
                        osc2.frequency.setValueAtTime(659.25, ctx.currentTime + 0.1); // E5
                        gain2.gain.setValueAtTime(0.1, ctx.currentTime + 0.1);
                        gain2.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.25);
                        osc2.connect(gain2);
                        gain2.connect(ctx.destination);
                        osc2.start(ctx.currentTime + 0.1);
                        osc2.stop(ctx.currentTime + 0.25);
                    } catch (e) {
                        console.warn("Browser tidak mendukung Web Audio API.", e);
                    }
                },

                playBeepFailure() {
                    try {
                        const ctx = this.getAudioContext();
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        
                        osc.type = 'sawtooth'; // Suara buzz
                        osc.frequency.setValueAtTime(120, ctx.currentTime); // Rendah
                        gain.gain.setValueAtTime(0.15, ctx.currentTime);
                        gain.gain.linearRampToValueAtTime(0.01, ctx.currentTime + 0.45);
                        osc.connect(gain);
                        gain.connect(ctx.destination);
                        
                        osc.start();
                        osc.stop(ctx.currentTime + 0.45);
                    } catch (e) {
                        console.warn("Browser tidak mendukung Web Audio API.", e);
                    }
                }
            }
        }
    </script>
</x-admin-layout>
