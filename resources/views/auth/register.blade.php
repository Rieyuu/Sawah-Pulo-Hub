<x-guest-layout>
    <div x-data="registerForm()">
        <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-gray-200 mb-6">Daftar Akun Wisatawan</h2>

        <!-- Error Alert -->
        <div x-show="errorMessage" x-transition class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            <span class="font-medium">Registrasi Gagal!</span> <span x-text="errorMessage"></span>
        </div>

        <form @submit.prevent="submitForm">
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Nama Lengkap')" />
                <x-text-input id="name" x-model="form.name" class="block mt-1 w-full" type="text" name="name" required autofocus placeholder="Masukkan nama lengkap" />
                <p x-show="errors.name" x-text="errors.name[0]" class="mt-1 text-sm text-red-600 dark:text-red-400"></p>
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" x-model="form.email" class="block mt-1 w-full" type="email" name="email" required placeholder="contoh@mail.com" />
                <p x-show="errors.email" x-text="errors.email[0]" class="mt-1 text-sm text-red-600 dark:text-red-400"></p>
            </div>

            <!-- WhatsApp Number -->
            <div class="mt-4">
                <x-input-label for="whatsapp" :value="__('Nomor WhatsApp')" />
                <x-text-input id="whatsapp" x-model="form.whatsapp" class="block mt-1 w-full" type="text" name="whatsapp" required placeholder="081234567890" />
                <p x-show="errors.whatsapp" x-text="errors.whatsapp[0]" class="mt-1 text-sm text-red-600 dark:text-red-400"></p>
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" x-model="form.password" class="block mt-1 w-full" type="password" name="password" required />
                <p x-show="errors.password" x-text="errors.password[0]" class="mt-1 text-sm text-red-600 dark:text-red-400"></p>
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                <x-text-input id="password_confirmation" x-model="form.password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-between mt-6">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md" href="{{ route('login') }}">
                    Sudah terdaftar? Masuk
                </a>

                <button type="submit" :disabled="loading" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <span x-show="loading" class="mr-2 animate-spin">&#9696;</span>
                    Daftar
                </button>
            </div>
        </form>
    </div>

    <script>
        function registerForm() {
            return {
                form: {
                    name: '',
                    email: '',
                    whatsapp: '',
                    password: '',
                    password_confirmation: ''
                },
                errors: {},
                errorMessage: '',
                loading: false,
                submitForm() {
                    this.loading = true;
                    this.errors = {};
                    this.errorMessage = '';

                    axios.post('/api/register', this.form)
                        .then(response => {
                            if (response.data.status === 201) {
                                // Simpan flash message di sessionStorage untuk login page
                                sessionStorage.setItem('register_success', 'Registrasi berhasil! Silakan masuk dengan akun Anda.');
                                window.location.href = '/login';
                            }
                        })
                        .catch(error => {
                            this.loading = false;
                            if (error.response && error.response.status === 422) {
                                this.errors = error.response.data.errors;
                            } else if (error.response && error.response.data.message) {
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
