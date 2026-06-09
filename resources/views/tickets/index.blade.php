<x-tourist-layout>
    <x-slot name="title">Pilihan Tiket Wisata | Sawah Pulo Hub</x-slot>

    <!-- Header Section -->
    <section class="relative bg-slate-900 text-white overflow-hidden py-20">
        <div class="absolute inset-0 opacity-30">
            <img src="https://images.unsplash.com/photo-1500937386664-56d159f8e281?auto=format&fit=crop&w=1920&q=80" alt="Tiket Sawah Pulo" class="w-full h-full object-cover object-center" />
            <div class="absolute inset-0 bg-slate-950"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight">Katalog Tiket Wisata</h1>
            <p class="text-slate-400 text-sm sm:text-base max-w-xl mx-auto">Pilih jenis tiket yang sesuai dengan rencana kunjungan rekreasi dan edukasi Anda.</p>
        </div>
    </section>

    <!-- Tiket List Section -->
    <section class="py-16 bg-white dark:bg-slate-900 transition-colors" x-data="checkoutVerifier()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="text-center max-w-2xl mx-auto mb-10 space-y-3">
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest block">Pembelian Tiket Resmi</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">Pilih & Beli Tiket Secara Online</h2>
                <p class="text-slate-500 text-sm">Pembayaran aman terintegrasi, e-ticket dapat langsung diunduh dan dicetak setelah diverifikasi admin.</p>
            </div>

            @if($tickets->isEmpty())
                <div class="text-center py-20 text-slate-400">
                    <p class="text-lg">Mohon maaf, saat ini belum ada tiket aktif yang tersedia.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($tickets as $ticket)
                        <div class="bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800/50 p-6 sm:p-8 rounded-3xl flex flex-col justify-between hover:shadow-md hover:border-slate-200 transition-all duration-300">
                            <div class="space-y-4">
                                <div class="w-full h-44 rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-900 border dark:border-slate-800/60 relative">
                                    <img src="{{ $ticket->image_path ?? 'https://images.unsplash.com/photo-1500937386664-56d159f8e281?auto=format&fit=crop&w=500&q=80' }}" alt="{{ $ticket->title }}" class="w-full h-full object-cover" />
                                </div>
                                <h3 class="font-bold text-slate-900 dark:text-white text-lg sm:text-xl">{{ $ticket->title }}</h3>
                                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">{{ $ticket->description }}</p>
                            </div>
                            
                            <div class="mt-8 space-y-4">
                                <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                                    Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                    <span class="text-xs font-normal text-slate-400">/orang</span>
                                </p>
                                <button @click="buyTicket({{ $ticket->id }})" class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-2xl transition-all duration-200 shadow-sm hover:shadow-md">
                                    Beli Tiket
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination Links -->
                <div class="pt-8">
                    {{ $tickets->links() }}
                </div>
            @endif

        </div>
    </section>

    <!-- Checkout Verifier Script -->
    <script>
        function checkoutVerifier() {
            return {
                buyTicket(ticketId) {
                    const token = localStorage.getItem('access_token');
                    const target = `/tickets/checkout/${ticketId}`;
                    if (!token) {
                        localStorage.setItem('redirect_target', target);
                        window.location.href = '/login';
                    } else {
                        window.location.href = target;
                    }
                }
            }
        }
    </script>
</x-tourist-layout>
