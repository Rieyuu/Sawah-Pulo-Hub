<x-tourist-layout>
    <x-slot name="title">Riwayat Pembelian | Sawah Pulo Hub</x-slot>

    <div class="max-w-4xl mx-auto px-4 py-12" x-data="bookingHistory()" x-init="checkAuth()">
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white mb-8">Riwayat Pembelian</h1>

        <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 sm:p-8 text-center py-16">
            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">Belum Ada Transaksi</h3>
            <p class="text-sm text-slate-500 mb-6">Anda belum pernah memesan tiket wisata Sawah Pulo.</p>
            <a href="/profile/settings#tiket" class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-2xl transition-all shadow-lg shadow-emerald-600/10">
                Pesan Tiket Sekarang
            </a>
        </div>
    </div>

    <script>
        function bookingHistory() {
            return {
                checkAuth() {
                    const token = localStorage.getItem('access_token');
                    if (!token) {
                        window.location.href = '/login';
                    }
                }
            }
        }
    </script>
</x-tourist-layout>
