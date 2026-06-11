<x-guest-layout>
    <div class="mb-6 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
        Lupa kata sandi Anda? Tidak masalah. Masukkan alamat email terdaftar Anda di bawah ini dan kami akan mengirimkan tautan pemulihan untuk mengatur ulang kata sandi Anda.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="contoh@mail.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6 gap-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 rounded-md" href="{{ route('login') }}">
                &larr; Kembali ke Login
            </a>

            <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 border border-transparent rounded-2xl font-bold text-xs text-white uppercase tracking-widest active:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-25 transition duration-150 shadow-md shadow-emerald-500/10">
                Kirim Tautan Pemulihan
            </button>
        </div>
    </form>
</x-guest-layout>

