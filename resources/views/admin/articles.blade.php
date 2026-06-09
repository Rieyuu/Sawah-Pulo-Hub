<x-admin-layout activeRoute="articles" header="Manajemen Artikel Edukasi">
    <div class="space-y-6" x-data="adminArticlesController()" x-init="initData()">
        
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <button @click="openCreateModal()" class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-2xl shadow-lg shadow-emerald-600/10 hover:shadow-emerald-600/20 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tulis Artikel Baru
                </button>
                
                <label class="flex items-center gap-2 cursor-pointer bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 px-4 py-3 rounded-2xl text-xs font-semibold select-none">
                    <input type="checkbox" x-model="withTrashed" @change="fetchArticles()" class="rounded text-emerald-600 focus:ring-emerald-500 border-slate-300">
                    Tampilkan Artikel Terhapus (Archive)
                </label>
            </div>
            
            <div class="text-sm text-slate-400">
                Total: <span class="font-bold text-slate-800 dark:text-slate-200" x-text="articles.length">0</span> Artikel
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
            <p class="text-sm text-slate-500">Memuat data artikel...</p>
        </div>

        <!-- Empty State -->
        <div x-show="!loading && articles.length === 0" class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-16 text-center space-y-4" x-cloak>
            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800/60 rounded-2xl flex items-center justify-center mx-auto text-slate-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            </div>
            <h4 class="text-lg font-bold text-slate-700 dark:text-slate-300">Belum Ada Artikel</h4>
            <p class="text-sm text-slate-400 max-w-sm mx-auto">Klik tombol 'Tulis Artikel Baru' di atas untuk mempublikasikan artikel edukasi pertama.</p>
        </div>

        <!-- Article Cards Grid -->
        <div x-show="!loading && articles.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-cloak>
            <template x-for="article in articles" :key="article.id">
                <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl overflow-hidden shadow-sm flex flex-col group relative" :class="article.deleted_at ? 'opacity-60 grayscale' : ''">
                    <!-- Image -->
                    <div class="h-48 bg-slate-100 dark:bg-slate-850 relative overflow-hidden">
                        <template x-if="article.image_path">
                            <img :src="article.image_path" alt="Article Image" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-300" />
                        </template>
                        <template x-if="!article.image_path">
                            <div class="w-full h-full flex items-center justify-center text-slate-300 bg-slate-100 dark:bg-slate-800">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        </template>
                        <!-- Category Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="text-[10px] px-2 py-1 font-bold rounded-lg bg-emerald-500 text-white shadow-md shadow-emerald-500/10" x-text="article.category ? article.category.name : 'Uncategorized'"></span>
                        </div>
                        <!-- Status Badge -->
                        <div class="absolute top-4 right-4">
                            <template x-if="article.deleted_at">
                                <span class="text-[10px] px-2 py-0.5 font-bold rounded-full bg-red-600 text-white shadow-md shadow-red-500/10">ARCHIVED</span>
                            </template>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <h4 class="text-lg font-bold text-slate-800 dark:text-slate-100 truncate" x-text="article.title"></h4>
                            <p class="text-xs text-slate-400 line-clamp-3" x-text="article.content"></p>
                        </div>

                        <!-- Audit Log -->
                        <div class="text-[10px] text-slate-400 bg-slate-50 dark:bg-slate-950 p-2.5 rounded-xl flex items-center justify-between">
                            <span>Penulis: <b class="text-slate-600 dark:text-slate-300" x-text="article.author ? article.author.name : 'System'"></b></span>
                            <span x-text="formatDate(article.updated_at)"></span>
                        </div>

                        <!-- Card Actions -->
                        <div class="pt-2 flex gap-2">
                            <template x-if="!article.deleted_at">
                                <div class="w-full flex gap-2">
                                    <button @click="openEditModal(article)" class="w-1/2 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700/80 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl transition-all">
                                        Edit Data
                                    </button>
                                    <button @click="deleteArticle(article.id)" class="w-1/2 py-2.5 bg-red-50 dark:bg-red-950/20 hover:bg-red-100/50 dark:hover:bg-red-950/40 text-red-600 dark:text-red-400 text-xs font-semibold rounded-xl transition-all">
                                        Arsipkan
                                    </button>
                                </div>
                            </template>
                            <template x-if="article.deleted_at">
                                <button @click="restoreArticle(article.id)" class="w-full py-2.5 bg-emerald-600/10 hover:bg-emerald-600/20 text-emerald-600 dark:text-emerald-400 text-xs font-bold rounded-xl transition-all">
                                    Pulihkan Artikel (Restore)
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
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white" x-text="modal.isEdit ? 'Ubah Artikel' : 'Tulis Artikel Baru'"></h3>
                            <button @click="modal.open = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white">&times;</button>
                        </div>

                        <form @submit.prevent="saveArticle" class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Judul Artikel</label>
                                <input type="text" x-model="modal.form.title" required class="w-full rounded-2xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4" placeholder="Misal: Panduan Menanam Sayur Organik di Rumah" />
                                <p x-show="modal.errors.title" x-text="modal.errors.title[0]" class="mt-1 text-xs text-red-600"></p>
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Kategori</label>
                                    <select x-model="modal.form.category_id" required class="w-full rounded-2xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4">
                                        <option value="">Pilih Kategori...</option>
                                        <template x-for="cat in categories" :key="cat.id">
                                            <option :value="cat.id" x-text="cat.name" :selected="cat.id == modal.form.category_id"></option>
                                        </template>
                                    </select>
                                    <p x-show="modal.errors.category_id" x-text="modal.errors.category_id[0]" class="mt-1 text-xs text-red-600"></p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Konten / Isi Artikel</label>
                                <textarea x-model="modal.form.content" required rows="6" class="w-full rounded-2xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4" placeholder="Ketik isi konten edukasi di sini..."></textarea>
                                <p x-show="modal.errors.content" x-text="modal.errors.content[0]" class="mt-1 text-xs text-red-600"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Gambar Sampul</label>
                                <input type="file" @change="handleImageUpload($event)" accept="image/*" class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 dark:file:bg-emerald-950/40 dark:file:text-emerald-300 file:cursor-pointer" />
                                <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG, SVG (Maks. 2MB). Biarkan kosong jika tidak ingin mengubah gambar.</p>
                                <p x-show="modal.errors.image" x-text="modal.errors.image[0]" class="mt-1 text-xs text-red-600"></p>
                            </div>

                            <div class="flex justify-end gap-2 border-t border-slate-100 dark:border-slate-800/80 pt-4 mt-6">
                                <button type="button" @click="modal.open = false" class="px-5 py-2.5 bg-slate-150 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl transition-all">
                                    Batal
                                </button>
                                <button type="submit" :disabled="modal.submitting" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-emerald-500/10">
                                    <span x-show="modal.submitting" class="inline-block animate-spin mr-1">&#9696;</span>
                                    Simpan Artikel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function adminArticlesController() {
            return {
                articles: [],
                categories: [],
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
                        content: '',
                        category_id: ''
                    },
                    imageFile: null,
                    errors: {}
                },
                initData() {
                    this.fetchCategories();
                    this.fetchArticles();
                },
                fetchCategories() {
                    axios.get('/api/categories')
                    .then(res => {
                        this.categories = res.data.data;
                    });
                },
                fetchArticles() {
                    this.loading = true;
                    const token = localStorage.getItem('access_token');
                    
                    axios.get(`/api/admin/articles?with_trashed=${this.withTrashed ? 1 : 0}`, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(res => {
                        this.articles = res.data.data;
                        this.loading = false;
                    })
                    .catch(err => {
                        this.loading = false;
                        this.showAlert('danger', 'Gagal memuat daftar artikel.');
                    });
                },
                openCreateModal() {
                    this.modal.isEdit = false;
                    this.modal.editId = null;
                    this.modal.form = {
                        title: '',
                        content: '',
                        category_id: ''
                    };
                    this.modal.imageFile = null;
                    this.modal.errors = {};
                    this.modal.open = true;
                },
                openEditModal(article) {
                    this.modal.isEdit = true;
                    this.modal.editId = article.id;
                    this.modal.form = {
                        title: article.title,
                        content: article.content,
                        category_id: article.category_id
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
                saveArticle() {
                    this.modal.submitting = true;
                    this.modal.errors = {};
                    const token = localStorage.getItem('access_token');

                    const formData = new FormData();
                    formData.append('title', this.modal.form.title);
                    formData.append('content', this.modal.form.content);
                    formData.append('category_id', this.modal.form.category_id);
                    if (this.modal.imageFile) {
                        formData.append('image', this.modal.imageFile);
                    }

                    let url = '/api/admin/articles';
                    if (this.modal.isEdit) {
                        url = `/api/admin/articles/${this.modal.editId}`;
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
                        this.fetchArticles();
                        this.showAlert('success', this.modal.isEdit ? 'Artikel berhasil diperbarui.' : 'Artikel baru berhasil dipublikasikan.');
                    })
                    .catch(err => {
                        this.modal.submitting = false;
                        if (err.response && err.response.status === 422) {
                            this.modal.errors = err.response.data.errors;
                        } else {
                            this.showAlert('danger', 'Gagal menyimpan artikel.');
                        }
                    });
                },
                deleteArticle(id) {
                    if (!confirm('Apakah Anda yakin ingin mengarsipkan artikel ini?')) return;
                    const token = localStorage.getItem('access_token');

                    axios.delete(`/api/admin/articles/${id}`, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(res => {
                        this.fetchArticles();
                        this.showAlert('success', 'Artikel berhasil diarsipkan.');
                    })
                    .catch(err => {
                        this.showAlert('danger', 'Gagal mengarsipkan artikel.');
                    });
                },
                restoreArticle(id) {
                    const token = localStorage.getItem('access_token');

                    axios.post(`/api/admin/articles/${id}/restore`, {}, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(res => {
                        this.fetchArticles();
                        this.showAlert('success', 'Artikel berhasil dipulihkan.');
                    })
                    .catch(err => {
                        this.showAlert('danger', 'Gagal memulihkan artikel.');
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
