<x-tourist-layout>
    <x-slot name="title">Pengaturan Profil | Sawah Pulo Hub</x-slot>

    <div class="max-w-4xl mx-auto px-4 py-12" x-data="profileSettingsForm()" x-init="fetchProfile()">
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white mb-8">Pengaturan Akun</h1>

        <!-- Loading State -->
        <div x-show="loadingData" class="flex flex-col items-center justify-center py-20 space-y-4">
            <div class="w-12 h-12 border-4 border-emerald-600 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-sm text-slate-600 font-medium dark:text-slate-300">Memuat profil Anda...</p>
        </div>

        <div x-show="!loadingData" class="space-y-8" x-cloak>
            
            <!-- Alert Messages -->
            <div x-show="successMessage" x-transition class="p-4 text-sm text-green-800 rounded-2xl bg-green-50 dark:bg-slate-900 dark:text-green-400 border border-green-100 dark:border-green-950" role="alert">
                <span class="font-medium">Berhasil!</span> <span x-text="successMessage"></span>
            </div>

            <div x-show="errorMessage" x-transition class="p-4 text-sm text-red-800 rounded-2xl bg-red-50 dark:bg-slate-900 dark:text-red-400 border border-red-100 dark:border-red-950" role="alert">
                <span class="font-medium">Gagal!</span> <span x-text="errorMessage"></span>
            </div>

            <!-- Profile Info Form -->
            <div class="bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/60 dark:shadow-none border border-emerald-100 dark:border-emerald-900/40 rounded-3xl p-6 sm:p-8">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Informasi Pribadi</h2>
                
                <form @submit.prevent="updateProfile">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nama Lengkap</label>
                            <input type="text" x-model="form.name" required class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 transition-all text-sm py-3 px-4" />
                            <p x-show="errors.name" x-text="errors.name[0]" class="mt-1 text-xs text-red-600"></p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Alamat Email</label>
                            <input type="email" x-model="form.email" required class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 transition-all text-sm py-3 px-4" />
                            <p x-show="errors.email" x-text="errors.email[0]" class="mt-1 text-xs text-red-600"></p>
                        </div>

                        <!-- WhatsApp -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nomor WhatsApp</label>
                            <input type="text" x-model="form.whatsapp" required class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 transition-all text-sm py-3 px-4" />
                            <p x-show="errors.whatsapp" x-text="errors.whatsapp[0]" class="mt-1 text-xs text-red-600"></p>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" :disabled="submittingProfile" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white text-sm font-semibold rounded-2xl shadow-lg shadow-emerald-600/10 hover:shadow-emerald-600/20 transition-all duration-200">
                            <span x-show="submittingProfile" class="inline-block animate-spin mr-1">&#9696;</span>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password Form -->
            <div class="bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/60 dark:shadow-none border border-emerald-100 dark:border-emerald-900/40 rounded-3xl p-6 sm:p-8">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Ubah Sandi</h2>
                <p class="text-sm text-slate-600 font-medium dark:text-slate-300 mb-6">Pastikan sandi Anda aman dengan kombinasi karakter unik.</p>

                <form @submit.prevent="updatePassword">
                    <div class="space-y-4">
                        <!-- Current Password -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Sandi Saat Ini</label>
                                <a href="{{ route('password.request') }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">Lupa kata sandi?</a>
                            </div>
                            <input type="password" x-model="pwdForm.current_password" required class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 transition-all text-sm py-3 px-4" />
                            <p x-show="errors.current_password" x-text="errors.current_password[0]" class="mt-1 text-xs text-red-600"></p>
                        </div>

                        <!-- New Password -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Sandi Baru</label>
                            <input type="password" x-model="pwdForm.password" required class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 transition-all text-sm py-3 px-4" />
                            <p x-show="errors.password" x-text="errors.password[0]" class="mt-1 text-xs text-red-600"></p>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Konfirmasi Sandi Baru</label>
                            <input type="password" x-model="pwdForm.password_confirmation" required class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 transition-all text-sm py-3 px-4" />
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" :disabled="submittingPassword" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white text-sm font-semibold rounded-2xl shadow-lg shadow-emerald-600/10 hover:shadow-emerald-600/20 transition-all duration-200">
                            <span x-show="submittingPassword" class="inline-block animate-spin mr-1">&#9696;</span>
                            Perbarui Sandi
                        </button>
                    </div>
                </form>
            </div>

            <!-- Danger Zone (Delete Account) -->
            <div class="bg-red-50/50 dark:bg-red-950/10 border border-red-100 dark:border-red-950/60 rounded-3xl p-6 sm:p-8">
                <h2 class="text-xl font-bold text-red-800 dark:text-red-400 mb-2">Hapus Akun</h2>
                <p class="text-sm text-red-700/80 dark:text-red-500 mb-6">Tindakan ini bersifat permanen. Seluruh riwayat pembelian Anda akan disimpan di database lokal kami untuk urusan administratif, namun akun Anda tidak lagi dapat diakses.</p>

                <button @click="showDeleteModal = true" class="px-5 py-3 bg-red-600 hover:bg-red-500 text-white text-sm font-semibold rounded-2xl transition-all duration-200">
                    Hapus Akun Saya
                </button>
            </div>

        </div>

        <!-- Deletion Confirmation Modal -->
        <div x-show="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                
                <!-- Backdrop -->
                <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showDeleteModal = false"></div>

                <!-- Center elements -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <!-- Modal box -->
                <div x-show="showDeleteModal" x-transition class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-emerald-100 dark:border-emerald-900/30">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-950/50 text-red-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Hapus Akun Permanen?</h3>
                                <p class="text-sm text-slate-600 font-medium dark:text-slate-300 mt-2">Apakah Anda yakin ingin menghapus akun? Anda akan langsung dikeluarkan dari sistem dan akun tidak bisa diakses kembali.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-900/40 px-6 py-4 flex flex-row-reverse gap-2">
                        <button @click="deleteAccount" :disabled="deleting" class="px-4 py-2.5 bg-red-600 hover:bg-red-500 disabled:opacity-50 text-white text-sm font-semibold rounded-xl transition-all">
                            <span x-show="deleting" class="inline-block animate-spin mr-1">&#9696;</span>
                            Ya, Hapus Akun
                        </button>
                        <button @click="showDeleteModal = false" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 text-sm font-semibold rounded-xl transition-all">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function profileSettingsForm() {
            return {
                loadingData: true,
                submittingProfile: false,
                submittingPassword: false,
                deleting: false,
                showDeleteModal: false,
                successMessage: '',
                errorMessage: '',
                form: {
                    name: '',
                    email: '',
                    whatsapp: ''
                },
                pwdForm: {
                    current_password: '',
                    password: '',
                    password_confirmation: ''
                },
                errors: {},
                fetchProfile() {
                    const token = localStorage.getItem('access_token');
                    if (!token) {
                        window.location.href = '/login';
                        return;
                    }

                    axios.get('/api/profile', {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(response => {
                        this.loadingData = false;
                        if (response.data.status === 200) {
                            this.form.name = response.data.data.user.name;
                            this.form.email = response.data.data.user.email;
                            this.form.whatsapp = response.data.data.user.whatsapp;
                        }
                    })
                    .catch(error => {
                        this.loadingData = false;
                        this.errorMessage = 'Gagal mengambil data profil Anda.';
                    });
                },
                updateProfile() {
                    this.submittingProfile = true;
                    this.errors = {};
                    this.successMessage = '';
                    this.errorMessage = '';
                    const token = localStorage.getItem('access_token');

                    axios.put('/api/profile', this.form, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(response => {
                        this.submittingProfile = false;
                        if (response.data.status === 200) {
                            this.successMessage = 'Informasi profil berhasil diperbarui.';
                            // Update local storage user data
                            localStorage.setItem('user_profile', JSON.stringify(response.data.data.user));
                        }
                    })
                    .catch(error => {
                        this.submittingProfile = false;
                        if (error.response && error.response.status === 422) {
                            this.errors = error.response.data.errors;
                        } else {
                            this.errorMessage = 'Gagal memperbarui profil.';
                        }
                    });
                },
                updatePassword() {
                    this.submittingPassword = true;
                    this.errors = {};
                    this.successMessage = '';
                    this.errorMessage = '';
                    const token = localStorage.getItem('access_token');

                    // Bind profile fields as well to satisfy request validator
                    const payload = {
                        ...this.form,
                        ...this.pwdForm
                    };

                    axios.put('/api/profile', payload, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(response => {
                        this.submittingPassword = false;
                        if (response.data.status === 200) {
                            this.successMessage = 'Kata sandi berhasil diperbarui.';
                            // Clear form
                            this.pwdForm.current_password = '';
                            this.pwdForm.password = '';
                            this.pwdForm.password_confirmation = '';
                        }
                    })
                    .catch(error => {
                        this.submittingPassword = false;
                        if (error.response && error.response.status === 422) {
                            this.errors = error.response.data.errors;
                        } else {
                            this.errorMessage = 'Gagal memperbarui kata sandi.';
                        }
                    });
                },
                deleteAccount() {
                    this.deleting = true;
                    const token = localStorage.getItem('access_token');

                    axios.delete('/api/profile', {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(response => {
                        this.deleting = false;
                        this.showDeleteModal = false;
                        if (response.data.status === 200) {
                            localStorage.clear();
                            sessionStorage.setItem('register_success', 'Akun Anda berhasil dihapus.');
                            window.location.href = '/login';
                        }
                    })
                    .catch(error => {
                        this.deleting = false;
                        this.showDeleteModal = false;
                        this.errorMessage = 'Terjadi kesalahan saat mencoba menghapus akun.';
                    });
                }
            }
        }
    </script>
</x-tourist-layout>
