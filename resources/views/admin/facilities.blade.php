<x-admin-layout activeRoute="facilities" header="Manajemen Fasilitas Wisata">
    <div class="space-y-6" x-data="adminFacilitiesController()" x-init="fetchFacilities()">
        
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <button @click="openCreateModal()" class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-2xl shadow-lg shadow-emerald-600/10 hover:shadow-emerald-600/20 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Fasilitas Baru
                </button>
                
                <label class="flex items-center gap-2 cursor-pointer bg-white dark:bg-slate-900 border border-emerald-100 dark:border-emerald-900/30 px-4 py-3 rounded-2xl text-xs font-semibold select-none">
                    <input type="checkbox" x-model="withTrashed" @change="fetchFacilities()" class="rounded text-emerald-600 focus:ring-emerald-500 border-slate-300">
                    Tampilkan Fasilitas Terhapus (Archive)
                </label>
            </div>
            
            <div class="text-sm text-slate-600 font-medium dark:text-slate-300">
                Total: <span class="font-bold text-slate-800 dark:text-slate-200" x-text="facilities.length">0</span> Fasilitas
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
            <p class="text-sm text-slate-600 font-medium dark:text-slate-300">Memuat data fasilitas...</p>
        </div>

        <!-- Empty State -->
        <div x-show="!loading && facilities.length === 0" class="bg-white dark:bg-slate-900 border border-emerald-100 dark:border-emerald-900/40 rounded-3xl p-16 text-center space-y-4" x-cloak>
            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800/60 rounded-2xl flex items-center justify-center mx-auto text-slate-600 font-medium dark:text-slate-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <h4 class="text-lg font-bold text-slate-700 dark:text-slate-300">Belum Ada Fasilitas</h4>
            <p class="text-sm text-slate-600 font-medium dark:text-slate-300 max-w-sm mx-auto">Klik tombol 'Tambah Fasilitas Baru' di atas untuk menambahkan fasilitas pertama di kawasan Sawah Pulo.</p>
        </div>

        <!-- Facility Cards Grid -->
        <div x-show="!loading && facilities.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-cloak>
            <template x-for="facility in facilities" :key="facility.id">
                <div class="bg-white dark:bg-slate-900 border border-emerald-100 dark:border-emerald-900/40 rounded-3xl overflow-hidden shadow-xl shadow-slate-200/60 dark:shadow-none hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-emerald-900/10 hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300 flex flex-col group relative hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-emerald-900/10 hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300" :class="facility.deleted_at ? 'opacity-60 grayscale' : ''">
                    <!-- Image -->
                    <div class="h-48 bg-slate-100 dark:bg-slate-800 relative overflow-hidden">
                        <template x-if="facility.image_path && facility.image_path !== '/storage/'">
                            <img :src="facility.image_path.startsWith('http') ? facility.image_path : '{{ asset('') }}' + facility.image_path.replace(/^\/+/, '')" alt="Facility Image" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out" />
                        </template>
                        <template x-if="!facility.image_path || facility.image_path === '/storage/'">
                            <div class="w-full h-full flex items-center justify-center text-slate-300 bg-slate-100 dark:bg-slate-800">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        </template>
                        <!-- Status Badge -->
                        <div class="absolute top-4 right-4">
                            <template x-if="facility.deleted_at">
                                <span class="text-[10px] px-2 py-0.5 font-bold rounded-full bg-red-600 text-white shadow-md shadow-red-500/10">ARCHIVED</span>
                            </template>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <h4 class="text-lg font-bold text-slate-800 dark:text-slate-100 truncate" x-text="facility.name"></h4>
                            <p class="text-xs text-slate-600 font-medium dark:text-slate-300 line-clamp-3" x-text="facility.description"></p>
                        </div>

                        <!-- Audit Log -->
                        <div class="text-[10px] text-slate-600 font-medium dark:text-slate-300 bg-slate-50 dark:bg-slate-950 p-2.5 rounded-xl flex items-center justify-between">
                            <span>Diupdate: <b class="text-slate-600 dark:text-slate-300" x-text="facility.user ? facility.user.name : 'System'"></b></span>
                            <span x-text="formatDate(facility.updated_at)"></span>
                        </div>

                        <!-- Card Actions -->
                        <div class="pt-2 flex gap-2">
                            <template x-if="!facility.deleted_at">
                                <div class="w-full flex gap-2">
                                    <button @click="openEditModal(facility)" class="w-1/2 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700/80 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl transition-all">
                                        Edit Data
                                    </button>
                                    <button @click="deleteFacility(facility.id)" class="w-1/2 py-2.5 bg-red-50 dark:bg-red-950/20 hover:bg-red-100/50 dark:hover:bg-red-950/40 text-red-600 dark:text-red-400 text-xs font-semibold rounded-xl transition-all">
                                        Arsipkan
                                    </button>
                                </div>
                            </template>
                            <template x-if="facility.deleted_at">
                                <button @click="restoreFacility(facility.id)" class="w-full py-2.5 bg-emerald-600/10 hover:bg-emerald-600/20 text-emerald-600 dark:text-emerald-400 text-xs font-bold rounded-xl transition-all">
                                    Pulihkan Fasilitas (Restore)
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
                <div x-show="modal.open" x-transition class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-emerald-100 dark:border-emerald-900/30">
                    <div class="p-6 sm:p-8 space-y-6">
                        <div class="flex justify-between items-center border-b border-emerald-100 dark:border-emerald-900/40 pb-4">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white" x-text="modal.isEdit ? 'Ubah Fasilitas' : 'Tambah Fasilitas Baru'"></h3>
                            <button @click="modal.open = false" class="text-slate-600 font-medium dark:text-slate-300 hover:text-slate-600 dark:hover:text-white">&times;</button>
                        </div>

                        <form @submit.prevent="saveFacility" class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nama Fasilitas</label>
                                <input type="text" x-model="modal.form.name" required class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4" placeholder="Misal: Gazebo Tengah Sawah" />
                                <p x-show="modal.errors.name" x-text="modal.errors.name[0]" class="mt-1 text-xs text-red-600"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Deskripsi Fasilitas</label>
                                <textarea x-model="modal.form.description" required rows="4" class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4" placeholder="Ketik deskripsi lengkap detail fasilitas..."></textarea>
                                <p x-show="modal.errors.description" x-text="modal.errors.description[0]" class="mt-1 text-xs text-red-600"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Gambar Fasilitas</label>
                                <input type="file" @change="handleImageUpload($event)" accept="image/*" class="w-full text-sm text-slate-600 font-medium dark:text-slate-300 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 dark:file:bg-emerald-950/40 dark:file:text-emerald-300 file:cursor-pointer" />
                                <p class="text-[10px] text-slate-600 font-medium dark:text-slate-300 mt-1">Format: JPG, PNG, SVG (Maks. 2MB). Biarkan kosong jika tidak ingin mengubah gambar.</p>
                                <p x-show="modal.errors.image" x-text="modal.errors.image[0]" class="mt-1 text-xs text-red-600"></p>
                            </div>

                            <div class="flex justify-end gap-2 border-t border-emerald-100 dark:border-emerald-900/40 pt-4 mt-6">
                                <button type="button" @click="modal.open = false" class="px-5 py-2.5 bg-slate-150 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl transition-all">
                                    Batal
                                </button>
                                <button type="submit" :disabled="modal.submitting" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-emerald-500/10">
                                    <span x-show="modal.submitting" class="inline-block animate-spin mr-1">&#9696;</span>
                                    Simpan Fasilitas
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function adminFacilitiesController() {
            return {
                facilities: [],
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
                        name: '',
                        description: ''
                    },
                    imageFile: null,
                    errors: {}
                },
                fetchFacilities() {
                    this.loading = true;
                    const token = localStorage.getItem('access_token');
                    
                    axios.get(`/api/admin/facilities?with_trashed=${this.withTrashed ? 1 : 0}`, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(res => {
                        this.facilities = res.data.data;
                        this.loading = false;
                    })
                    .catch(err => {
                        this.loading = false;
                        this.showAlert('danger', 'Gagal memuat daftar fasilitas wisata.');
                    });
                },
                openCreateModal() {
                    this.modal.isEdit = false;
                    this.modal.editId = null;
                    this.modal.form = {
                        name: '',
                        description: ''
                    };
                    this.modal.imageFile = null;
                    this.modal.errors = {};
                    this.modal.open = true;
                },
                openEditModal(facility) {
                    this.modal.isEdit = true;
                    this.modal.editId = facility.id;
                    this.modal.form = {
                        name: facility.name,
                        description: facility.description
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
                saveFacility() {
                    this.modal.submitting = true;
                    this.modal.errors = {};
                    const token = localStorage.getItem('access_token');

                    const formData = new FormData();
                    formData.append('name', this.modal.form.name);
                    formData.append('description', this.modal.form.description);
                    if (this.modal.imageFile) {
                        formData.append('image', this.modal.imageFile);
                    }

                    let url = '/api/admin/facilities';
                    if (this.modal.isEdit) {
                        url = `/api/admin/facilities/${this.modal.editId}`;
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
                        this.fetchFacilities();
                        this.showAlert('success', this.modal.isEdit ? 'Fasilitas berhasil diperbarui.' : 'Fasilitas baru berhasil ditambahkan.');
                    })
                    .catch(err => {
                        this.modal.submitting = false;
                        if (err.response && err.response.status === 422) {
                            this.modal.errors = err.response.data.errors;
                        } else {
                            this.showAlert('danger', 'Gagal menyimpan data fasilitas.');
                        }
                    });
                },
                deleteFacility(id) {
                    if (!confirm('Apakah Anda yakin ingin mengarsipkan fasilitas ini?')) return;
                    const token = localStorage.getItem('access_token');

                    axios.delete(`/api/admin/facilities/${id}`, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(res => {
                        this.fetchFacilities();
                        this.showAlert('success', 'Fasilitas berhasil diarsipkan.');
                    })
                    .catch(err => {
                        this.showAlert('danger', 'Gagal mengarsipkan fasilitas.');
                    });
                },
                restoreFacility(id) {
                    const token = localStorage.getItem('access_token');

                    axios.post(`/api/admin/facilities/${id}/restore`, {}, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(res => {
                        this.fetchFacilities();
                        this.showAlert('success', 'Fasilitas berhasil dipulihkan.');
                    })
                    .catch(err => {
                        this.showAlert('danger', 'Gagal memulihkan fasilitas.');
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
                formatDate(dateStr) {
                    if (!dateStr) return '';
                    const d = new Date(dateStr);
                    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                }
            }
        }
    </script>
</x-admin-layout>
