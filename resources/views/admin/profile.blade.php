<x-admin-layout activeRoute="profile" header="Pengaturan Akun Saya">
    <div class="max-w-4xl mx-auto space-y-8 animate-fade-in" x-data="adminProfileController()" x-init="fetchProfile()">
        
        <!-- Alerts -->
        <div x-show="successMessage" x-transition class="p-4 text-sm text-green-800 rounded-2xl bg-green-50 dark:bg-slate-900 dark:text-green-400 border border-green-150 dark:border-green-950" role="alert" x-cloak>
            <span class="font-medium">Berhasil!</span> <span x-text="successMessage"></span>
        </div>

        <div x-show="errorMessage" x-transition class="p-4 text-sm text-red-800 rounded-2xl bg-red-50 dark:bg-slate-900 dark:text-red-400 border border-red-150 dark:border-red-950" role="alert" x-cloak>
            <span class="font-medium">Gagal!</span> <span x-text="errorMessage"></span>
        </div>

        <!-- Form Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Left Side: Profile Info Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <div class="pb-3 border-b border-slate-100 dark:border-slate-800/80">
                    <h3 class="font-bold text-slate-900 dark:text-white">Informasi Akun</h3>
                    <p class="text-xs text-slate-400">Perbarui informasi email, nama lengkap, dan nomor WhatsApp Anda.</p>
                </div>

                <form @submit.prevent="updateProfile()" class="space-y-4">
                    <!-- Name -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-400 uppercase">Nama Lengkap</label>
                        <input type="text" x-model="profileForm.name" required :disabled="loading" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs font-semibold" />
                        <p x-show="profileErrors.name" x-text="profileErrors.name[0]" class="text-[10px] text-red-600 dark:text-red-400 mt-1"></p>
                    </div>

                    <!-- Email -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-400 uppercase">Alamat Email</label>
                        <input type="email" x-model="profileForm.email" required :disabled="loading" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs font-semibold" />
                        <p x-show="profileErrors.email" x-text="profileErrors.email[0]" class="text-[10px] text-red-600 dark:text-red-400 mt-1"></p>
                    </div>

                    <!-- WhatsApp -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-400 uppercase">Nomor WhatsApp</label>
                        <input type="text" x-model="profileForm.whatsapp" required :disabled="loading" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs font-semibold" />
                        <p x-show="profileErrors.whatsapp" x-text="profileErrors.whatsapp[0]" class="text-[10px] text-red-600 dark:text-red-400 mt-1"></p>
                    </div>

                    <button type="submit" :disabled="loading" class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white text-xs font-bold rounded-xl shadow-lg transition-all">
                        <span x-show="loading" class="inline-block animate-spin mr-1">&#9696;</span>
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- Right Side: Change Password Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <div class="pb-3 border-b border-slate-100 dark:border-slate-800/80">
                    <h3 class="font-bold text-slate-900 dark:text-white">Keamanan & Kata Sandi</h3>
                    <p class="text-xs text-slate-400">Ganti kata sandi Anda secara berkala untuk menjaga keamanan akun.</p>
                </div>

                <form @submit.prevent="updatePassword()" class="space-y-4">
                    <!-- Current Password -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-400 uppercase">Sandi Saat Ini</label>
                        <input type="password" x-model="passwordForm.current_password" required :disabled="loading" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs font-semibold" />
                        <p x-show="passwordErrors.current_password" x-text="passwordErrors.current_password[0]" class="text-[10px] text-red-600 dark:text-red-400 mt-1"></p>
                    </div>

                    <!-- New Password -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-400 uppercase">Sandi Baru</label>
                        <input type="password" x-model="passwordForm.password" required :disabled="loading" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs font-semibold" />
                        <p x-show="passwordErrors.password" x-text="passwordErrors.password[0]" class="text-[10px] text-red-600 dark:text-red-400 mt-1"></p>
                    </div>

                    <!-- Confirm New Password -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-400 uppercase">Konfirmasi Sandi Baru</label>
                        <input type="password" x-model="passwordForm.password_confirmation" required :disabled="loading" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs font-semibold" />
                        <p x-show="passwordErrors.password_confirmation" x-text="passwordErrors.password_confirmation[0]" class="text-[10px] text-red-600 dark:text-red-400 mt-1"></p>
                    </div>

                    <button type="submit" :disabled="loading || !passwordForm.current_password || !passwordForm.password" class="w-full py-3 bg-slate-800 hover:bg-slate-700 disabled:opacity-50 text-white text-xs font-bold rounded-xl shadow-lg transition-all">
                        <span x-show="loading" class="inline-block animate-spin mr-1">&#9696;</span>
                        Perbarui Sandi
                    </button>
                </form>
            </div>

        </div>

    </div>

    <script>
        function adminProfileController() {
            return {
                profileForm: {
                    name: '',
                    email: '',
                    whatsapp: ''
                },
                passwordForm: {
                    current_password: '',
                    password: '',
                    password_confirmation: ''
                },
                profileErrors: {},
                passwordErrors: {},
                successMessage: '',
                errorMessage: '',
                loading: false,

                // Web Audio Context for synthesized beep sounds
                audioCtx: null,

                fetchProfile() {
                    const token = localStorage.getItem('access_token');
                    
                    axios.get('/api/profile', {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(res => {
                        this.profileForm.name = res.data.data.user.name;
                        this.profileForm.email = res.data.data.user.email;
                        this.profileForm.whatsapp = res.data.data.user.whatsapp;
                    })
                    .catch(err => {
                        this.errorMessage = 'Gagal memuat informasi profil.';
                    });
                },

                updateProfile() {
                    this.loading = true;
                    this.profileErrors = {};
                    this.successMessage = '';
                    this.errorMessage = '';
                    const token = localStorage.getItem('access_token');

                    axios.put('/api/profile', this.profileForm, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(res => {
                        this.loading = false;
                        this.successMessage = 'Informasi akun Anda berhasil diperbarui.';
                        this.playBeepSuccess();

                        // Update local storage so sidebar changes dynamically
                        const profileStr = localStorage.getItem('user_profile');
                        if (profileStr) {
                            const profile = JSON.parse(profileStr);
                            profile.name = res.data.data.user.name;
                            profile.email = res.data.data.user.email;
                            profile.whatsapp = res.data.data.user.whatsapp;
                            localStorage.setItem('user_profile', JSON.stringify(profile));
                            
                            // Emit global event or update layout directly if accessible
                            if (window.adminLayoutController) {
                                // Fallback refresh or update directly if layout controller has reference
                            }
                        }
                    })
                    .catch(err => {
                        this.loading = false;
                        this.playBeepFailure();
                        if (err.response && err.response.status === 422) {
                            this.profileErrors = err.response.data.errors;
                        } else {
                            this.errorMessage = 'Terjadi kesalahan sistem saat memperbarui profil.';
                        }
                    });
                },

                updatePassword() {
                    this.loading = true;
                    this.passwordErrors = {};
                    this.successMessage = '';
                    this.errorMessage = '';
                    const token = localStorage.getItem('access_token');

                    // Combine profile info with password to match the PUT /api/profile endpoint payload
                    const payload = {
                        name: this.profileForm.name,
                        email: this.profileForm.email,
                        whatsapp: this.profileForm.whatsapp,
                        current_password: this.passwordForm.current_password,
                        password: this.passwordForm.password,
                        password_confirmation: this.passwordForm.password_confirmation
                    };

                    axios.put('/api/profile', payload, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                    .then(res => {
                        this.loading = false;
                        this.successMessage = 'Kata sandi Anda berhasil diperbarui.';
                        this.playBeepSuccess();

                        // Reset password fields
                        this.passwordForm.current_password = '';
                        this.passwordForm.password = '';
                        this.passwordForm.password_confirmation = '';
                    })
                    .catch(err => {
                        this.loading = false;
                        this.playBeepFailure();
                        if (err.response && err.response.status === 422) {
                            this.passwordErrors = err.response.data.errors;
                        } else {
                            this.errorMessage = 'Gagal memperbarui kata sandi.';
                        }
                    });
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
                        const osc1 = ctx.createOscillator();
                        const gain1 = ctx.createGain();
                        osc1.type = 'sine';
                        osc1.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
                        gain1.gain.setValueAtTime(0.08, ctx.currentTime);
                        gain1.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.12);
                        osc1.connect(gain1);
                        gain1.connect(ctx.destination);
                        osc1.start();
                        osc1.stop(ctx.currentTime + 0.12);

                        const osc2 = ctx.createOscillator();
                        const gain2 = ctx.createGain();
                        osc2.type = 'sine';
                        osc2.frequency.setValueAtTime(698.46, ctx.currentTime + 0.12); // F5
                        gain2.gain.setValueAtTime(0.08, ctx.currentTime + 0.12);
                        gain2.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.28);
                        osc2.connect(gain2);
                        gain2.connect(ctx.destination);
                        osc2.start(ctx.currentTime + 0.12);
                        osc2.stop(ctx.currentTime + 0.28);
                    } catch (e) {
                        console.warn(e);
                    }
                },

                playBeepFailure() {
                    try {
                        const ctx = this.getAudioContext();
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.type = 'sawtooth';
                        osc.frequency.setValueAtTime(140, ctx.currentTime);
                        gain.gain.setValueAtTime(0.12, ctx.currentTime);
                        gain.gain.linearRampToValueAtTime(0.01, ctx.currentTime + 0.35);
                        osc.connect(gain);
                        gain.connect(ctx.destination);
                        osc.start();
                        osc.stop(ctx.currentTime + 0.35);
                    } catch (e) {
                        console.warn(e);
                    }
                }
            }
        }
    </script>
</x-admin-layout>
