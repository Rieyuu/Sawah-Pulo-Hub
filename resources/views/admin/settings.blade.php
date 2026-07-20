<x-admin-layout activeRoute="settings" header="Pengaturan Kawasan Wisata">
    <div class="space-y-6" x-data="adminSettingsController()" x-init="fetchSettings()">
        
        <!-- Tab Buttons -->
        <div class="flex border-b border-emerald-100 dark:border-emerald-900/30 gap-4 overflow-x-auto pb-px">
            <button @click="activeTab = 'profile'" class="py-3 px-4 text-sm font-bold border-b-2 transition-all flex items-center gap-2" :class="activeTab === 'profile' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-600 font-medium dark:text-slate-300 hover:text-slate-200'">
                Profil Kawasan Wisata
            </button>
            <button @click="activeTab = 'homepage'" class="py-3 px-4 text-sm font-bold border-b-2 transition-all flex items-center gap-2" :class="activeTab === 'homepage' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-600 font-medium dark:text-slate-300 hover:text-slate-200'">
                Kontak & Tampilan Utama
            </button>
            <button @click="activeTab = 'siteplan'" class="py-3 px-4 text-sm font-bold border-b-2 transition-all flex items-center gap-2" :class="activeTab === 'siteplan' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-600 font-medium dark:text-slate-300 hover:text-slate-200'">
                Denah Peta 2D Site Plan
            </button>
            <button @click="activeTab = 'payment'" class="py-3 px-4 text-sm font-bold border-b-2 transition-all flex items-center gap-2" :class="activeTab === 'payment' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-600 font-medium dark:text-slate-300 hover:text-slate-200'">
                Metode Pembayaran
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
            <p class="text-sm text-slate-600 font-medium dark:text-slate-300">Memuat pengaturan kawasan...</p>
        </div>

        <!-- Form Content -->
        <form @submit.prevent="saveSettings" x-show="!loading" class="space-y-8" x-cloak>
            
            <!-- Tab: Profile (Tentang Kami / Sejarah / Visi Misi) -->
            <div x-show="activeTab === 'profile'" class="bg-white dark:bg-slate-900 border border-emerald-100 dark:border-emerald-900/40 rounded-3xl p-6 sm:p-8 space-y-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Profil Wisata Sawah Pulo</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Sejarah Singkat Wisata</label>
                        <textarea x-model="form.about_history" rows="4" class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Visi</label>
                            <textarea x-model="form.about_vision" rows="4" class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Misi (Gunakan baris baru untuk poin terpisah)</label>
                            <textarea x-model="form.about_mission" rows="4" class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4"></textarea>
                        </div>
                    </div>

                    <div class="border-t border-emerald-100 dark:border-emerald-900/40 pt-4">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Gambar Struktur Organisasi</label>
                        <div class="flex items-center gap-6">
                            <div class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-2xl overflow-hidden border border-emerald-100 dark:border-emerald-900/30">
                                <template x-if="images.about_structure_image">
                                    <img :src="images.about_structure_image" alt="Structure Image" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out" />
                                </template>
                            </div>
                            <div class="flex-grow">
                                <input type="file" @change="handleImageUpload($event, 'about_structure_image')" accept="image/*" class="w-full text-sm text-slate-600 font-medium dark:text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 dark:file:bg-emerald-950/40 dark:file:text-emerald-300 file:cursor-pointer" />
                                <p class="text-[10px] text-slate-600 font-medium dark:text-slate-300 mt-1">Format: JPG, PNG, SVG (Maks. 2MB)</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-emerald-100 dark:border-emerald-900/40 pt-6">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Gambar Utama Profil Wisata (Tentang Wisata)</label>
                        <div class="flex items-center gap-6">
                            <div class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-2xl overflow-hidden border border-emerald-100 dark:border-emerald-900/30">
                                <template x-if="images.about_image">
                                    <img :src="images.about_image" alt="About Image" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out" />
                                </template>
                            </div>
                            <div class="flex-grow">
                                <input type="file" @change="handleImageUpload($event, 'about_image')" accept="image/*" class="w-full text-sm text-slate-600 font-medium dark:text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 dark:file:bg-emerald-950/40 dark:file:text-emerald-300 file:cursor-pointer" />
                                <p class="text-[10px] text-slate-600 font-medium dark:text-slate-300 mt-1">Format: JPG, PNG, SVG (Maks. 2MB)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Homepage (Hero & Footer, Jam & Kontak) -->
            <div x-show="activeTab === 'homepage'" class="bg-white dark:bg-slate-900 border border-emerald-100 dark:border-emerald-900/40 rounded-3xl p-6 sm:p-8 space-y-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white border-b border-emerald-100 dark:border-emerald-900/30 pb-3">Pengaturan Tampilan Beranda & Kaki Halaman (Footer)</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Judul Hero (Bisa HTML, misal: Keindahan Alam &lt;span class="text-emerald-500"&gt;Edukasi&lt;/span&gt;)</label>
                        <input type="text" x-model="form.hero_title" class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Deskripsi Kaki Halaman (Footer)</label>
                        <textarea x-model="form.footer_description" rows="2" class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Subjudul Hero (Tagline)</label>
                        <textarea x-model="form.hero_subtitle" rows="2" class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4"></textarea>
                    </div>
                    
                    <div class="md:col-span-2 border-b border-emerald-100 dark:border-emerald-900/30 pb-6">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Gambar Background Hero (Halaman Utama)</label>
                        <div class="flex items-center gap-6">
                            <div class="w-32 h-20 bg-slate-100 dark:bg-slate-800 rounded-2xl overflow-hidden border border-emerald-100 dark:border-emerald-900/30">
                                <template x-if="images.hero_bg_image">
                                    <img :src="images.hero_bg_image" alt="Hero Background" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out" />
                                </template>
                            </div>
                            <div class="flex-grow">
                                <input type="file" @change="handleImageUpload($event, 'hero_bg_image')" accept="image/*" class="w-full text-sm text-slate-600 font-medium dark:text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 dark:file:bg-emerald-950/40 dark:file:text-emerald-300 file:cursor-pointer" />
                                <p class="text-[10px] text-slate-600 font-medium dark:text-slate-300 mt-1">Format: JPG, PNG, SVG (Maks. 2MB)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="text-lg font-bold text-slate-900 dark:text-white pt-4">Hari, Jam Buka, & Kontak Sosial Media</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Hari Operasional</label>
                        <input type="text" x-model="form.operating_days" class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4" placeholder="Misal: Senin - Minggu" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Jam Buka - Tutup</label>
                        <input type="text" x-model="form.operating_hours" class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4" placeholder="Misal: 08:00 - 17:00 WIB" />
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Alamat Fisik</label>
                        <textarea x-model="form.contact_address" rows="3" class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nomor WhatsApp Resmi (Format Internasional)</label>
                        <input type="text" x-model="form.contact_whatsapp" class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4" placeholder="Misal: 6281234567890" />
                        <p class="text-[10px] text-slate-600 font-medium dark:text-slate-300 mt-1">Harus diawali kode negara (misal 62) tanpa karakter spasi atau tanda tambah (+).</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Username Instagram</label>
                        <input type="text" x-model="form.contact_instagram" class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4" placeholder="Tautan lengkap profil Instagram" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Tautan Profil TikTok</label>
                        <input type="text" x-model="form.contact_tiktok" class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Tautan Profil X (Twitter)</label>
                        <input type="text" x-model="form.contact_x" class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Link Navigasi Google Maps (Tautan Utama)</label>
                        <input type="text" x-model="form.contact_maps_url" class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4" placeholder="https://maps.app.goo.gl/..." />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Link Embed Google Maps (Iframe Src)</label>
                        <input type="text" x-model="form.contact_maps_embed" class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4" placeholder="https://www.google.com/maps/embed?pb=..." />
                        <p class="text-[10px] text-slate-600 font-medium dark:text-slate-300 mt-1">Masukkan URL dari atribut 'src' pada kode embed HTML Google Maps.</p>
                    </div>



                </div>
            </div>

            <!-- Tab: Site Plan (2D Site Plan Layout Image) -->
            <div x-show="activeTab === 'siteplan'" class="bg-white dark:bg-slate-900 border border-emerald-100 dark:border-emerald-900/40 rounded-3xl p-6 sm:p-8 space-y-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Denah Kawasan 2D Site Plan</h3>
                
                <div class="space-y-4">
                    <p class="text-sm text-slate-600 font-medium dark:text-slate-300 leading-relaxed">
                        Denah peta kawasan 2D berfungsi menampilkan peta visual tata letak fasilitas pariwisata di halaman depan agar wisatawan dapat memahami titik lokasi dengan mudah.
                    </p>

                    <div class="border-t border-emerald-100 dark:border-emerald-900/40 pt-4 space-y-4">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">File Peta Denah 2D</label>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                            <div class="w-48 h-32 bg-slate-100 dark:bg-slate-800 rounded-2xl overflow-hidden border border-emerald-100 dark:border-emerald-900/30">
                                <template x-if="images.site_plan_image">
                                    <img :src="images.site_plan_image" alt="Site Plan Image" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out" />
                                </template>
                            </div>
                            <div class="flex-grow">
                                <input type="file" @change="handleImageUpload($event, 'site_plan_image')" accept="image/*" class="w-full text-sm text-slate-600 font-medium dark:text-slate-300 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 dark:file:bg-emerald-950/40 dark:file:text-emerald-300 file:cursor-pointer" />
                                <p class="text-[10px] text-slate-600 font-medium dark:text-slate-300 mt-1">Format: JPG, PNG, SVG (Maks. 2MB)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Payment (QRIS & Timeout) -->
            <div x-show="activeTab === 'payment'" class="bg-white dark:bg-slate-900 border border-emerald-100 dark:border-emerald-900/40 rounded-3xl p-6 sm:p-8 space-y-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Pengaturan Metode Pembayaran & Batas Waktu</h3>

                <div class="space-y-6">
                    <!-- QRIS Upload -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Gambar QR Code / QRIS Resmi</label>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                            <div class="w-32 h-32 bg-slate-100 dark:bg-slate-800 rounded-2xl overflow-hidden border border-emerald-100 dark:border-emerald-900/30 flex items-center justify-center p-2">
                                <template x-if="images.payment_qris_image">
                                    <img :src="images.payment_qris_image" alt="QRIS Image" class="w-full h-full object-contain" />
                                </template>
                            </div>
                            <div class="flex-grow">
                                <input type="file" @change="handleImageUpload($event, 'payment_qris_image')" accept="image/*" class="w-full text-sm text-slate-600 font-medium dark:text-slate-300 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 dark:file:bg-emerald-950/40 dark:file:text-emerald-300 file:cursor-pointer" />
                                <p class="text-[10px] text-slate-600 font-medium dark:text-slate-300 mt-1">Format: JPG, PNG, SVG (Maks. 2MB)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Details -->
                    <div class="border-t border-emerald-100 dark:border-emerald-900/40 pt-6">
                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 mb-4">Detail Rekening Bank Transfer Resmi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nama Bank</label>
                                <input type="text" x-model="form.payment_bank_name" class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4" placeholder="Misal: Bank Mandiri" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nomor Rekening</label>
                                <input type="text" x-model="form.payment_bank_account" class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4" placeholder="Misal: 1420012345678" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nama Pemilik Rekening (a.n.)</label>
                                <input type="text" x-model="form.payment_bank_recipient" class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4" placeholder="Misal: BUMDes Sawah Pulo" />
                            </div>
                        </div>
                    </div>

                    <!-- Timeout input -->
                    <div class="max-w-xs">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Batas Waktu Transfer Pembayaran (Jam)</label>
                        <div class="flex items-center gap-3">
                            <input type="number" min="1" max="168" x-model.number="form.payment_timeout_hours" class="w-24 rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4 font-bold" />
                            <span class="text-xs text-slate-600 font-medium dark:text-slate-300">Jam setelah pesanan dibuat.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Bar -->
            <div class="flex justify-end gap-3 pt-4">
                <button type="submit" :disabled="submitting" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white text-sm font-semibold rounded-2xl shadow-lg shadow-emerald-600/10 hover:shadow-emerald-600/20 transition-all duration-200">
                    <span x-show="submitting" class="inline-block animate-spin mr-1">&#9696;</span>
                    Simpan Semua Pengaturan
                </button>
            </div>

        </form>

    </div>

    <script>
        function adminSettingsController() {
            return {
                activeTab: 'profile',
                loading: true,
                submitting: false,
                alert: {
                    show: false,
                    type: 'success',
                    message: ''
                },
                form: {
                    about_history: '',
                    about_vision: '',
                    about_mission: '',
                    operating_days: '',
                    operating_hours: '',
                    contact_address: '',
                    contact_whatsapp: '',
                    contact_instagram: '',
                    contact_tiktok: '',
                    contact_x: '',
                    contact_maps_url: '',
                    contact_maps_embed: '',
                    payment_timeout_hours: 2,
                    hero_title: '',
                    hero_subtitle: '',
                    footer_description: '',
                    payment_bank_name: '',
                    payment_bank_account: '',
                    payment_bank_recipient: '',
                },
                images: {
                    about_structure_image: null,
                    site_plan_image: null,
                    payment_qris_image: null,
                    hero_bg_image: null,
                    about_image: null,
                },
                uploadedFiles: {
                    about_structure_image: null,
                    site_plan_image: null,
                    payment_qris_image: null,
                    hero_bg_image: null,
                    about_image: null,
                },
                fetchSettings() {
                    this.loading = true;
                    const token = localStorage.getItem('access_token');

                    axios.get('/api/admin/settings', {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(res => {
                        const data = res.data.data;
                        
                        // Parse values
                        for (let key in this.form) {
                            if (data[key]) {
                                this.form[key] = data[key].value;
                            }
                        }

                        // Parse images
                        for (let key in this.images) {
                            if (data[key]) {
                                this.images[key] = data[key].value;
                            }
                        }

                        this.loading = false;
                    })
                    .catch(err => {
                        this.loading = false;
                        this.showAlert('danger', 'Gagal memuat pengaturan.');
                    });
                },
                handleImageUpload(e, key) {
                    if (e.target.files.length > 0) {
                        const file = e.target.files[0];
                        this.uploadedFiles[key] = file;
                        
                        // Local preview
                        const reader = new FileReader();
                        reader.onload = (event) => {
                            this.images[key] = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },
                saveSettings() {
                    this.submitting = true;
                    const token = localStorage.getItem('access_token');

                    // Auto-extract src if user pasted full iframe HTML tag
                    let embedInput = this.form.contact_maps_embed;
                    if (embedInput && embedInput.includes('<iframe')) {
                        const match = embedInput.match(/src=["']([^"']+)["']/);
                        if (match && match[1]) {
                            this.form.contact_maps_embed = match[1];
                        }
                    }

                    const formData = new FormData();
                    
                    // Add text fields
                    for (let key in this.form) {
                        formData.append(key, this.form[key] || '');
                    }

                    // Add image files
                    for (let key in this.uploadedFiles) {
                        if (this.uploadedFiles[key]) {
                            formData.append(key, this.uploadedFiles[key]);
                        }
                    }

                    axios.post('/api/admin/settings', formData, {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(res => {
                        this.submitting = false;
                        this.fetchSettings();
                        this.showAlert('success', 'Semua pengaturan berhasil diperbarui.');
                    })
                    .catch(err => {
                        this.submitting = false;
                        this.showAlert('danger', 'Terjadi kesalahan saat menyimpan pengaturan.');
                    });
                },
                showAlert(type, message) {
                    this.alert.show = true;
                    this.alert.type = type;
                    this.alert.message = message;
                    setTimeout(() => {
                        this.alert.show = false;
                    }, 5000);
                }
            }
        }
    </script>
</x-admin-layout>
