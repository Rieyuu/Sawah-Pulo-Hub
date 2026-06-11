<x-admin-layout activeRoute="tickets" header="Manajemen Tiket Wisata">
    <div class="space-y-6" x-data="adminTicketsController()" x-init="fetchTickets()">
        
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <button @click="openCreateModal()" class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-2xl shadow-lg shadow-emerald-600/10 hover:shadow-emerald-600/20 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Tiket Baru
                </button>
                
                <label class="flex items-center gap-2 cursor-pointer bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 px-4 py-3 rounded-2xl text-xs font-semibold select-none">
                    <input type="checkbox" x-model="withTrashed" @change="fetchTickets()" class="rounded text-emerald-600 focus:ring-emerald-500 border-slate-300">
                    Tampilkan Tiket Terhapus (Archive)
                </label>
            </div>
            
            <div class="text-sm text-slate-400">
                Total: <span class="font-bold text-slate-800 dark:text-slate-200" x-text="tickets.length">0</span> Tiket
            </div>
        </div>

        <!-- Alert messages -->
        <div x-show="alert.show" x-transition class="p-4 rounded-2xl border text-sm flex justify-between items-center" :class="alert.type === 'success' ? 'bg-green-50 border-green-200 text-green-800 dark:bg-slate-900 dark:border-green-950 dark:text-green-400' : 'bg-red-50 border-red-200 text-red-800 dark:bg-slate-900 dark:border-red-950 dark:text-red-400'" role="alert" x-cloak>
            <span x-text="alert.message"></span>
            <button @click="alert.show = false" class="hover:opacity-70">&times;</button>
        </div>

        <!-- Loading Spinner -->
        <div x-show="loading" class="flex flex-col items-center justify-center py-20 space-y-4">
            <div class="w-12 h-12 border-4 border-emerald-600 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-sm text-slate-500">Memuat data tiket...</p>
        </div>

        <!-- Empty State -->
        <div x-show="!loading && tickets.length === 0" class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-16 text-center space-y-4" x-cloak>
            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800/60 rounded-2xl flex items-center justify-center mx-auto text-slate-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v2M4 18h16"></path></svg>
            </div>
            <h4 class="text-lg font-bold text-slate-700 dark:text-slate-300">Belum Ada Tiket Wisata</h4>
            <p class="text-sm text-slate-400 max-w-sm mx-auto">Klik tombol 'Tambah Tiket Baru' di atas untuk meluncurkan opsi tiket wisata pertama Anda.</p>
        </div>

        <!-- Ticket Cards Grid -->
        <div x-show="!loading && tickets.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-cloak>
            <template x-for="ticket in tickets" :key="ticket.id">
                <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl overflow-hidden shadow-sm flex flex-col group relative" :class="ticket.deleted_at ? 'opacity-60 grayscale' : ''">
                    <!-- Image -->
                    <div class="h-48 bg-slate-100 dark:bg-slate-800 relative overflow-hidden">
                        <template x-if="ticket.image_path">
                            <img :src="ticket.image_path" alt="Ticket Image" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-300" />
                        </template>
                        <template x-if="!ticket.image_path">
                            <div class="w-full h-full flex items-center justify-center text-slate-300 bg-slate-100 dark:bg-slate-800">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        </template>
                        <!-- Status Badge -->
                        <div class="absolute top-4 right-4 flex gap-1.5">
                            <span class="text-[10px] px-2 py-0.5 font-bold rounded-full text-white shadow-md shadow-emerald-500/10" :class="ticket.is_active ? 'bg-emerald-600' : 'bg-slate-500'">
                                <span x-text="ticket.is_active ? 'Aktif' : 'Nonaktif'"></span>
                            </span>
                            <template x-if="ticket.deleted_at">
                                <span class="text-[10px] px-2 py-0.5 font-bold rounded-full bg-red-600 text-white shadow-md shadow-red-500/10">ARCHIVED</span>
                            </template>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <h4 class="text-lg font-bold text-slate-800 dark:text-slate-100 truncate" x-text="ticket.title"></h4>
                            <p class="text-xs text-slate-400 line-clamp-3" x-text="ticket.description"></p>
                        </div>

                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800/80 flex justify-between items-center text-sm">
                            <div>
                                <p class="text-xs text-slate-400">Harga</p>
                                <p class="font-extrabold text-emerald-600 dark:text-emerald-400">Rp <span x-text="formatNumber(ticket.price)"></span></p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-slate-400">Stok Harian</p>
                                <p class="font-bold text-slate-700 dark:text-slate-300" x-text="ticket.stock"></p>
                            </div>
                        </div>

                        <!-- Audit Log -->
                        <div class="text-[10px] text-slate-400 bg-slate-50 dark:bg-slate-950 p-2.5 rounded-xl flex items-center justify-between">
                            <span>Diupdate: <b class="text-slate-600 dark:text-slate-300" x-text="ticket.user ? ticket.user.name : 'System'"></b></span>
                            <span x-text="formatDate(ticket.updated_at)"></span>
                        </div>

                        <!-- Card Actions -->
                        <div class="pt-2 flex gap-2">
                            <template x-if="!ticket.deleted_at">
                                <div class="w-full flex gap-2">
                                    <button @click="openEditModal(ticket)" class="w-1/2 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700/80 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl transition-all">
                                        Edit Data
                                    </button>
                                    <button @click="deleteTicket(ticket.id)" class="w-1/2 py-2.5 bg-red-50 dark:bg-red-950/20 hover:bg-red-100/50 dark:hover:bg-red-950/40 text-red-600 dark:text-red-400 text-xs font-semibold rounded-xl transition-all">
                                        Arsipkan
                                    </button>
                                </div>
                            </template>
                            <template x-if="ticket.deleted_at">
                                <button @click="restoreTicket(ticket.id)" class="w-full py-2.5 bg-emerald-600/10 hover:bg-emerald-600/20 text-emerald-600 dark:text-emerald-400 text-xs font-bold rounded-xl transition-all">
                                    Pulihkan Tiket (Restore)
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Create/Edit Modal -->
        <div x-show="modal.open" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Backdrop -->
                <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="modal.open = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <!-- Modal Content -->
                <div x-show="modal.open" x-transition class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100 dark:border-slate-800">
                    <div class="p-6 sm:p-8 space-y-6">
                        <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800/80 pb-4">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white" x-text="modal.isEdit ? 'Ubah Tiket Wisata' : 'Tambah Tiket Wisata'"></h3>
                            <button @click="modal.open = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white">&times;</button>
                        </div>

                        <form @submit.prevent="saveTicket" class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nama Tiket</label>
                                <input type="text" x-model="modal.form.title" required class="w-full rounded-2xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4" placeholder="Misal: Tiket Masuk Eduwisata Weekend" />
                                <p x-show="modal.errors.title" x-text="modal.errors.title[0]" class="mt-1 text-xs text-red-600"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Deskripsi Tiket</label>
                                <textarea x-model="modal.form.description" required rows="3" class="w-full rounded-2xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4" placeholder="Ketik deskripsi lengkap detail tiket..."></textarea>
                                <p x-show="modal.errors.description" x-text="modal.errors.description[0]" class="mt-1 text-xs text-red-600"></p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Harga (Rupiah)</label>
                                    <input type="number" x-model="modal.form.price" required min="0" class="w-full rounded-2xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4" />
                                    <p x-show="modal.errors.price" x-text="modal.errors.price[0]" class="mt-1 text-xs text-red-600"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Stok Harian</label>
                                    <input type="number" x-model="modal.form.stock" required min="0" class="w-full rounded-2xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4" />
                                    <p x-show="modal.errors.stock" x-text="modal.errors.stock[0]" class="mt-1 text-xs text-red-600"></p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Gambar Tiket</label>
                                <input type="file" @change="handleImageUpload($event)" accept="image/*" class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 dark:file:bg-emerald-950/40 dark:file:text-emerald-300 file:cursor-pointer" />
                                <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG, SVG (Maks. 2MB). Biarkan kosong jika tidak ingin mengubah gambar.</p>
                                <p x-show="modal.errors.image" x-text="modal.errors.image[0]" class="mt-1 text-xs text-red-600"></p>
                            </div>

                            <div class="flex items-center gap-2 pt-2">
                                <input type="checkbox" id="isActive" x-model="modal.form.is_active" class="rounded text-emerald-600 focus:ring-emerald-500 border-slate-300">
                                <label for="isActive" class="text-sm font-semibold text-slate-700 dark:text-slate-300 select-none cursor-pointer">Tiket ini Aktif (Bisa dibeli wisatawan)</label>
                            </div>

                            <div class="flex justify-end gap-2 border-t border-slate-100 dark:border-slate-800/80 pt-4 mt-6">
                                <button type="button" @click="modal.open = false" class="px-5 py-2.5 bg-slate-150 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl transition-all">
                                    Batal
                                </button>
                                <button type="submit" :disabled="modal.submitting" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-emerald-500/10">
                                    <span x-show="modal.submitting" class="inline-block animate-spin mr-1">&#9696;</span>
                                    Simpan Tiket
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function adminTicketsController() {
            return {
                tickets: [],
                loading: true,
                withTrashed: false,
                alert: {
                    show: false,
                    type: 'success',
                    message: ''
                },
                modal: {
                    open: false,
                    isEdit: false,
                    submitting: false,
                    editId: null,
                    form: {
                        title: '',
                        description: '',
                        price: 0,
                        stock: 0,
                        is_active: true
                    },
                    imageFile: null,
                    errors: {}
                },
                fetchTickets() {
                    this.loading = true;
                    const token = localStorage.getItem('access_token');
                    
                    axios.get(`/api/admin/tickets?with_trashed=${this.withTrashed ? 1 : 0}`, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(res => {
                        this.tickets = res.data.data;
                        this.loading = false;
                    })
                    .catch(err => {
                        this.loading = false;
                        this.showAlert('danger', 'Gagal memuat daftar tiket wisata.');
                    });
                },
                openCreateModal() {
                    this.modal.isEdit = false;
                    this.modal.editId = null;
                    this.modal.form = {
                        title: '',
                        description: '',
                        price: 0,
                        stock: 0,
                        is_active: true
                    };
                    this.modal.imageFile = null;
                    this.modal.errors = {};
                    this.modal.open = true;
                },
                openEditModal(ticket) {
                    this.modal.isEdit = true;
                    this.modal.editId = ticket.id;
                    this.modal.form = {
                        title: ticket.title,
                        description: ticket.description,
                        price: Math.round(ticket.price),
                        stock: ticket.stock,
                        is_active: !!ticket.is_active
                    };
                    this.modal.imageFile = null;
                    this.modal.errors = {};
                    this.modal.open = true;
                },
                handleImageUpload(e) {
                    if (e.target.files.length > 0) {
                        this.modal.imageFile = e.target.files[0];
                    }
                },
                saveTicket() {
                    this.modal.submitting = true;
                    this.modal.errors = {};
                    const token = localStorage.getItem('access_token');

                    const formData = new FormData();
                    formData.append('title', this.modal.form.title);
                    formData.append('description', this.modal.form.description);
                    formData.append('price', this.modal.form.price);
                    formData.append('stock', this.modal.form.stock);
                    formData.append('is_active', this.modal.form.is_active ? '1' : '0');
                    if (this.modal.imageFile) {
                        formData.append('image', this.modal.imageFile);
                    }

                    // For editing, we upload via POST with spoofed method
                    let url = '/api/admin/tickets';
                    if (this.modal.isEdit) {
                        url = `/api/admin/tickets/${this.modal.editId}`;
                        formData.append('_method', 'PUT');
                    }

                    axios.post(url, formData, {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(res => {
                        this.modal.submitting = false;
                        this.modal.open = false;
                        this.fetchTickets();
                        this.showAlert('success', this.modal.isEdit ? 'Tiket berhasil diperbarui.' : 'Tiket baru berhasil dibuat.');
                    })
                    .catch(err => {
                        this.modal.submitting = false;
                        if (err.response && err.response.status === 422) {
                            this.modal.errors = err.response.data.errors;
                        } else {
                            this.showAlert('danger', 'Gagal menyimpan tiket wisata.');
                        }
                    });
                },
                deleteTicket(id) {
                    if (!confirm('Apakah Anda yakin ingin mengarsipkan tiket ini?')) return;
                    const token = localStorage.getItem('access_token');

                    axios.delete(`/api/admin/tickets/${id}`, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(res => {
                        this.fetchTickets();
                        this.showAlert('success', 'Tiket berhasil diarsipkan (soft deleted).');
                    })
                    .catch(err => {
                        this.showAlert('danger', 'Gagal mengarsipkan tiket.');
                    });
                },
                restoreTicket(id) {
                    const token = localStorage.getItem('access_token');

                    axios.post(`/api/admin/tickets/${id}/restore`, {}, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(res => {
                        this.fetchTickets();
                        this.showAlert('success', 'Tiket berhasil dipulihkan.');
                    })
                    .catch(err => {
                        this.showAlert('danger', 'Gagal memulihkan tiket.');
                    });
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
