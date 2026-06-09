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
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" x-model="form.password" class="block mt-1 w-full" type="password" name="password" required />
                <p x-show="errors.password" x-text="errors.password" class="mt-1 text-sm text-red-600 dark:text-red-400"></p>
            </div>

            <div class="flex items-center justify-between mt-6">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md" href="{{ route('register') }}">
                    Belum punya akun? Daftar
                </a>

                <button type="submit" :disabled="loading" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <span x-show="loading" class="mr-2 animate-spin">&#9696;</span>
                    {{ __('Log in') }}
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
                                const redirectTarget = localStorage.getItem('redirect_target');
                                if (redirectTarget) {
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
