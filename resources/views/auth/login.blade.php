<x-guest-layout>
    <div x-data="loginForm()" x-init="checkFlashMessage()">
        <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-gray-200 mb-6">Masuk ke Sawah Pulo Hub</h2>

        <!-- Success Alert (Flash Message) -->
        <div x-show="successMessage" x-transition class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <span class="font-medium">Berhasil!</span> <span x-text="successMessage"></span>
        </div>

        <!-- Error Alert -->
        <div x-show="errorMessage" x-transition class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            <span class="font-medium">Gagal Masuk!</span> <span x-text="errorMessage"></span>
        </div>

        <form @submit.prevent="submitForm">
            <!-- Email / WhatsApp Input -->
            <div>
                <x-input-label for="identifier" :value="__('Email atau No. WhatsApp')" />
                <x-text-input id="identifier" x-model="form.identifier" class="block mt-1 w-full" type="text" name="identifier" required autofocus placeholder="contoh@mail.com atau 0812..." />
                <p x-show="errors.identifier" x-text="errors.identifier" class="mt-1 text-sm text-red-600 dark:text-red-400"></p>
            </div>

            <!-- Password -->
            <div class="mt-4" x-data="{ showPassword: false }">
                <x-input-label for="password" :value="__('Password')" />
                <div class="relative mt-1">
                    <x-text-input id="password" x-model="form.password" class="block w-full pr-10" x-bind:type="showPassword ? 'text' : 'password'" name="password" required />
                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <!-- Eye open (show password) -->
                        <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <!-- Eye closed (hide password) -->
                        <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                    </button>
                </div>
                <p x-show="errors.password" x-text="errors.password" class="mt-1 text-sm text-red-600 dark:text-red-400"></p>
            </div>

            <div class="flex flex-col gap-3 mt-6">
                <div class="flex items-center justify-between text-xs sm:text-sm">
                    <a class="underline text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 rounded-md" href="{{ route('register') }}">
                        Belum punya akun? Daftar
                    </a>
                    <a class="underline text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 rounded-md" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                </div>

                <button type="submit" :disabled="loading" class="w-full mt-2 inline-flex items-center justify-center px-4 py-3 bg-emerald-600 hover:bg-emerald-500 border border-transparent rounded-2xl font-bold text-xs text-white uppercase tracking-widest active:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-25 transition duration-150 shadow-md shadow-emerald-500/10">
                    <span x-show="loading" class="mr-2 animate-spin">&#9696;</span>
                    {{ __('Masuk') }}
                </button>
            </div>
        </form>
    </div>

    <script>
        function loginForm() {
            return {
                form: {
                    identifier: '',
                    password: ''
                },
                errors: {},
                errorMessage: '',
                successMessage: '',
                loading: false,
                checkFlashMessage() {
                    const msg = sessionStorage.getItem('register_success');
                    if (msg) {
                        this.successMessage = msg;
                        sessionStorage.removeItem('register_success');
                    }
                },
                submitForm() {
                    this.loading = true;
                    this.errors = {};
                    this.errorMessage = '';

                    axios.post('/api/login', this.form)
                        .then(response => {
                            if (response.data.status === 200) {
                                // Simpan token dan profil
                                localStorage.setItem('access_token', response.data.data.access_token);
                                localStorage.setItem('user_profile', JSON.stringify(response.data.data.user));
                                
                                // Set global Axios header
                                window.axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.data.access_token}`;

                                // Redirect
                                const user = response.data.data.user;
                                const redirectTarget = localStorage.getItem('redirect_target');
                                
                                if (user.roles && user.roles.includes('admin')) {
                                    window.location.href = '/admin/dashboard';
                                } else if (redirectTarget) {
                                    localStorage.removeItem('redirect_target');
                                    window.location.href = redirectTarget;
                                } else {
                                    window.location.href = '/';
                                }
                            }
                        })
                        .catch(error => {
                            this.loading = false;
                            if (error.response && error.response.status === 422) {
                                this.errors = error.response.data.errors;
                            } else if (error.response && (error.response.status === 401 || error.response.status === 403)) {
                                this.errorMessage = error.response.data.message;
                            } else {
                                this.errorMessage = 'Terjadi kesalahan sistem. Silakan coba lagi.';
                            }
                        });
                }
            }
        }
    </script>
</x-guest-layout>
