<x-tourist-layout>
    <x-slot name="title">Sawah Pulo Hub - Eduwisata Pertanian & Alam Pedesaan</x-slot>

    <!-- SEO Meta (Optional helper layout slots) -->
    <meta name="description" content="Sawah Pulo Hub adalah pusat eduwisata alam pedesaan ramah lingkungan yang memadukan pertanian tradisional dengan edukasi modern. Temukan petualangan menarik bersama keluarga." />

    <!-- 1. Hero Section -->
    <section class="relative bg-slate-900 text-white overflow-hidden py-32 sm:py-40">
        <!-- Background Image overlay -->
        <div class="absolute inset-0 opacity-40">
            <img src="https://images.unsplash.com/photo-1500937386664-56d159f8e281?auto=format&fit=crop&w=1920&q=80" alt="Sawah Pulo Background" class="w-full h-full object-cover object-center" />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/50 to-transparent"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-8">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold bg-emerald-500/10 text-emerald-400 rounded-full border border-emerald-500/20">
                🌱 Selamat Datang di Sawah Pulo Hub
            </span>
            <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-white max-w-3xl mx-auto leading-tight">
                Keindahan Alam Pedesaan & <span class="bg-gradient-to-r from-emerald-400 to-teal-300 bg-clip-text text-transparent">Edukasi Pertanian</span>
            </h1>
            <p class="text-lg text-slate-300 max-w-2xl mx-auto">
                Rasakan pengalaman edukatif bercocok tanam hidroponik, budidaya ternak, dan keindahan panorama sawah hijau yang menenangkan jiwa.
            </p>
            <div class="flex flex-wrap justify-center gap-4 pt-4">
                <a href="#tiket" class="px-6 py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-2xl shadow-lg shadow-emerald-600/30 transition-all duration-200">
                    Pesan Tiket Sekarang
                </a>
                <a href="#fasilitas" class="px-6 py-3.5 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-2xl border border-white/20 transition-all duration-200">
                    Jelajahi Fasilitas
                </a>
            </div>
        </div>
    </section>

    <!-- 2. Profil Wisata Section -->
    <section id="wisata" class="py-24 bg-white dark:bg-slate-900 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white">Profil Wisata Sawah Pulo</h2>
                <p class="text-slate-500 text-sm sm:text-base">Mengenal lebih dekat visi, misi, sejarah, dan tujuan berdirinya eduwisata ramah lingkungan ini.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Text / Info -->
                <div class="space-y-6">
                    <div class="space-y-2">
                        <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Sejarah Singkat</span>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white">Awal Mula Pendirian</h3>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-sm sm:text-base">
                        {{ \App\Models\SiteSetting::getValue('about_history', 'Sawah Pulo Hub didirikan sebagai kawasan eduwisata pertanian modern terpadu yang memadukan keindahan alam pedesaan dengan metode agribisnis berkelanjutan.') }}
                    </p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4">
                        <div class="p-5 bg-slate-50 dark:bg-slate-800/40 rounded-3xl space-y-2 border border-slate-100/50 dark:border-slate-800/50">
                            <h4 class="font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                                <span class="text-emerald-500 text-lg">🎯</span> Visi Kami
                            </h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                "{{ \App\Models\SiteSetting::getValue('about_vision', 'Menjadi destinasi agrowisata edukatif terkemuka yang melestarikan alam.') }}"
                            </p>
                        </div>

                        <div class="p-5 bg-slate-50 dark:bg-slate-800/40 rounded-3xl space-y-2 border border-slate-100/50 dark:border-slate-800/50">
                            <h4 class="font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                                <span class="text-emerald-500 text-lg">🚀</span> Misi Kami
                            </h4>
                            <div class="text-xs text-slate-500 leading-relaxed whitespace-pre-line">{{ \App\Models\SiteSetting::getValue('about_mission', "1. Edukasi masyarakat tentang pertanian modern.\n2. Mengembangkan pariwisata ramah lingkungan.") }}</div>
                        </div>
                    </div>
                </div>

                <!-- Structure Image -->
                <div class="space-y-4">
                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest block text-center lg:text-left">Struktur Organisasi</span>
                    <div class="bg-slate-50 dark:bg-slate-800/40 p-4 rounded-3xl border border-slate-100 dark:border-slate-800/50 shadow-sm">
                        <img src="{{ \App\Models\SiteSetting::getValue('about_structure_image', 'https://images.unsplash.com/photo-1531538606174-0f90ff5dce83?auto=format&fit=crop&w=800&q=80') }}" alt="Struktur Organisasi" class="w-full h-auto rounded-2xl object-cover" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Fasilitas & 2D Site Plan Section -->
    <section id="fasilitas" class="py-24 bg-slate-50 dark:bg-slate-950 transition-colors border-y border-slate-100 dark:border-slate-800/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white">Peta Denah & Fasilitas Wisata</h2>
                <p class="text-slate-500 text-sm sm:text-base">Lihat layout 2D kawasan wisata kami dan temukan berbagai fasilitas menarik yang tersebar di dalamnya.</p>
            </div>

            <!-- 2D Site Plan Preview Card -->
            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-100 dark:border-slate-800 rounded-3xl overflow-hidden p-6 sm:p-8 mb-16">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-center">
                    
                    <!-- Text Info (2 cols) -->
                    <div class="lg:col-span-2 space-y-6">
                        <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Peta 2D Kawasan</span>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white">Denah Eduwisata</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            Denah layout 2D Sawah Pulo Hub membantu Anda menavigasi kawasan kami yang luas. Temukan lokasi area sawah tradisional, lab hidroponik, mini zoo, gardu pandang, dan spot edukasi peternakan secara mudah.
                        </p>
                        
                        <div class="pt-2">
                            <a href="#hubungi" class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-2xl shadow-md transition-all">
                                Lihat Rincian Alamat & Lokasi
                            </a>
                        </div>
                    </div>

                    <!-- Image Preview (3 cols) -->
                    <div class="lg:col-span-3 bg-slate-50 dark:bg-slate-950 p-2 rounded-2xl border border-slate-100 dark:border-slate-800 cursor-zoom-in" x-data="{ showSitePlanFull: false }">
                        <img @click="showSitePlanFull = true" src="{{ \App\Models\SiteSetting::getValue('site_plan_image', 'https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=1200&q=80') }}" alt="2D Site Plan Sawah Pulo" class="w-full h-auto rounded-xl shadow-inner object-cover hover:opacity-95 transition-opacity" />

                        <!-- Full Screen Image Modal -->
                        <div x-show="showSitePlanFull" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/90 backdrop-blur-sm" x-cloak>
                            <div class="relative max-w-5xl w-full" @click.away="showSitePlanFull = false">
                                <button @click="showSitePlanFull = false" class="absolute -top-12 right-0 text-white hover:text-slate-300 text-sm font-semibold flex items-center gap-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    Tutup Denah
                                </button>
                                <img src="{{ \App\Models\SiteSetting::getValue('site_plan_image', 'https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=1200&q=80') }}" alt="2D Site Plan Sawah Pulo Full" class="w-full h-auto rounded-2xl shadow-2xl max-h-[80vh] object-contain mx-auto" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Facilities Grid (Dynamic from Database) -->
            <div class="space-y-8">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Highlight Fasilitas Utama</h3>
                
                @php
                    $facilities = \App\Models\Facility::take(4)->get();
                @endphp

                @if($facilities->isEmpty())
                    <!-- Fallback default facilities if database empty -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Facility 1 -->
                        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl overflow-hidden hover:shadow-lg transition-all duration-200">
                            <img src="https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?auto=format&fit=crop&w=500&q=80" alt="Hidroponik" class="w-full h-48 object-cover" />
                            <div class="p-5 space-y-2">
                                <h4 class="font-bold text-slate-900 dark:text-white">Greenhouse Hidroponik</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">Belajar menanam sayur organik menggunakan media air dan nutrisi ramah lingkungan.</p>
                            </div>
                        </div>
                        <!-- Facility 2 -->
                        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl overflow-hidden hover:shadow-lg transition-all duration-200">
                            <img src="https://images.unsplash.com/photo-1500595046783-cd2117939a68?auto=format&fit=crop&w=500&q=80" alt="Mini Zoo" class="w-full h-48 object-cover" />
                            <div class="p-5 space-y-2">
                                <h4 class="font-bold text-slate-900 dark:text-white">Peternakan Mini Zoo</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">Berinteraksi secara langsung dan memberi makan kelinci, kambing, serta ternak sehat.</p>
                            </div>
                        </div>
                        <!-- Facility 3 -->
                        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl overflow-hidden hover:shadow-lg transition-all duration-200">
                            <img src="https://images.unsplash.com/photo-1500937386664-56d159f8e281?auto=format&fit=crop&w=500&q=80" alt="Panorama Sawah" class="w-full h-48 object-cover" />
                            <div class="p-5 space-y-2">
                                <h4 class="font-bold text-slate-900 dark:text-white">Panorama Jembatan Sawah</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">Spot swafoto di atas jembatan kayu melintasi hamparan sawah hijau yang indah.</p>
                            </div>
                        </div>
                        <!-- Facility 4 -->
                        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl overflow-hidden hover:shadow-lg transition-all duration-200">
                            <img src="https://images.unsplash.com/photo-1589156280159-27698a70f29e?auto=format&fit=crop&w=500&q=80" alt="Kuliner" class="w-full h-48 object-cover" />
                            <div class="p-5 space-y-2">
                                <h4 class="font-bold text-slate-900 dark:text-white">Sentra Kuliner Tradisional</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">Menikmati hidangan kuliner khas pedesaan hasil bumi petani Sawah Pulo.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Dynamic database list -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($facilities as $facility)
                            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl overflow-hidden hover:shadow-lg transition-all duration-200">
                                <img src="{{ $facility->image_path ?? 'https://images.unsplash.com/photo-1500937386664-56d159f8e281?auto=format&fit=crop&w=500&q=80' }}" alt="{{ $facility->name }}" class="w-full h-48 object-cover" />
                                <div class="p-5 space-y-2">
                                    <h4 class="font-bold text-slate-900 dark:text-white">{{ $facility->name }}</h4>
                                    <p class="text-xs text-slate-500 leading-relaxed">{{ Str::limit($facility->description, 120) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- 4. Tiket Section -->
    <section id="tiket" class="py-24 bg-white dark:bg-slate-900 transition-colors" x-data="checkoutVerifier()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white">Pilihan Tiket Wisata</h2>
                <p class="text-slate-500 text-sm sm:text-base">Dapatkan tiket masuk resmi Sawah Pulo secara online dengan mudah, cepat, dan aman.</p>
            </div>

            @php
                $tickets = \App\Models\Ticket::where('is_active', true)->get();
            @endphp

            @if($tickets->isEmpty())
                <!-- Fallback tickets if database empty -->
                <div class="max-w-md mx-auto bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 p-8 rounded-3xl text-center space-y-6">
                    <div class="text-slate-400 text-4xl">🎟️</div>
                    <div class="space-y-1">
                        <h3 class="font-bold text-slate-900 dark:text-white">Tiket Masuk Reguler</h3>
                        <p class="text-xs text-slate-500">Akses masuk seluruh area sawah, jembatan panorama, greenhouse hidroponik, dan peternakan mini zoo.</p>
                    </div>
                    <p class="text-2xl font-black text-emerald-600">Rp 15.000 <span class="text-xs font-normal text-slate-400">/orang</span></p>
                    <button @click="buyTicket(1)" class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-2xl transition-all">
                        Beli Tiket Masuk
                    </button>
                </div>
            @else
                <!-- Dynamic database tickets -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 justify-center">
                    @foreach($tickets as $ticket)
                        <div class="bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800/50 p-8 rounded-3xl flex flex-col justify-between hover:shadow-md transition-all">
                            <div class="space-y-4">
                                <div class="w-full h-40 rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-900 border dark:border-slate-800">
                                    <img src="{{ $ticket->image_path ?? 'https://images.unsplash.com/photo-1500937386664-56d159f8e281?auto=format&fit=crop&w=500&q=80' }}" alt="{{ $ticket->title }}" class="w-full h-full object-cover" />
                                </div>
                                <h3 class="font-bold text-slate-900 dark:text-white text-lg">{{ $ticket->title }}</h3>
                                <p class="text-xs text-slate-500 leading-relaxed">{{ $ticket->description }}</p>
                            </div>
                            <div class="mt-8 space-y-4">
                                <p class="text-2xl font-black text-emerald-600">
                                    Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                    <span class="text-xs font-normal text-slate-400">/orang</span>
                                </p>
                                <button @click="buyTicket({{ $ticket->id }})" class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-2xl transition-all">
                                    Beli Tiket
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- 5. Artikel Section -->
    <section id="artikel" class="py-24 bg-slate-50 dark:bg-slate-950 transition-colors border-t border-slate-100 dark:border-slate-800/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white">Artikel & Berita Terbaru</h2>
                <p class="text-slate-500 text-sm sm:text-base">Temukan wawasan pertanian organik, tips berkebun hidroponik, dan informasi kegiatan di Sawah Pulo.</p>
            </div>

            @php
                $articles = \App\Models\Article::latest()->take(3)->get();
            @endphp

            @if($articles->isEmpty())
                <!-- Fallback articles if database empty -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Article 1 -->
                    <div class="bg-white dark:bg-slate-900 rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-all border border-slate-100 dark:border-slate-800">
                        <img src="https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?auto=format&fit=crop&w=500&q=80" alt="Hidroponik" class="w-full h-48 object-cover" />
                        <div class="p-6 space-y-3">
                            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/30 px-2.5 py-1 rounded-full">Edukasi</span>
                            <h3 class="font-bold text-slate-900 dark:text-white">Metode Hidroponik untuk Pemula</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">Temukan langkah-langkah praktis memulai menanam sayuran menggunakan media air di pekarangan rumah...</p>
                        </div>
                    </div>
                    <!-- Article 2 -->
                    <div class="bg-white dark:bg-slate-900 rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-all border border-slate-100 dark:border-slate-800">
                        <img src="https://images.unsplash.com/photo-1599599810769-bcde5a160d32?auto=format&fit=crop&w=500&q=80" alt="Organik" class="w-full h-48 object-cover" />
                        <div class="p-6 space-y-3">
                            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/30 px-2.5 py-1 rounded-full">Budidaya</span>
                            <h3 class="font-bold text-slate-900 dark:text-white">Pentingnya Pupuk Kompos Organik</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">Mengapa pupuk organik jauh lebih baik bagi kelestarian kesuburan tanah jangka panjang dibanding kimia...</p>
                        </div>
                    </div>
                    <!-- Article 3 -->
                    <div class="bg-white dark:bg-slate-900 rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-all border border-slate-100 dark:border-slate-800">
                        <img src="https://images.unsplash.com/photo-1500937386664-56d159f8e281?auto=format&fit=crop&w=500&q=80" alt="Wisata" class="w-full h-48 object-cover" />
                        <div class="p-6 space-y-3">
                            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/30 px-2.5 py-1 rounded-full">Event</span>
                            <h3 class="font-bold text-slate-900 dark:text-white">Festival Panen Raya Sawah Pulo</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">Keseruan festival menyambut panen padi musim ini dengan berbagai tari tradisional dan pentas rakyat...</p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Dynamic database articles -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($articles as $article)
                        <div class="bg-white dark:bg-slate-900 rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-all border border-slate-100 dark:border-slate-800">
                            <img src="{{ $article->image_path ?? 'https://images.unsplash.com/photo-1500937386664-56d159f8e281?auto=format&fit=crop&w=500&q=80' }}" alt="{{ $article->title }}" class="w-full h-48 object-cover" />
                            <div class="p-6 space-y-3">
                                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/30 px-2.5 py-1 rounded-full">{{ $article->category->name ?? 'Wisata' }}</span>
                                <h3 class="font-bold text-slate-900 dark:text-white">{{ $article->title }}</h3>
                                <p class="text-xs text-slate-500 leading-relaxed">{{ Str::limit(strip_tags($article->content), 120) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- 6. Hubungi Kami & Peta Terintegrasi Section -->
    <section id="hubungi" class="py-24 bg-white dark:bg-slate-900 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <!-- Info & CTA -->
                <div class="space-y-8">
                    <div class="space-y-4">
                        <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Kontak & Lokasi</span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white">Kunjungi Destinasi Kami</h2>
                        <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                            Kami sangat senang menyambut kunjungan Anda bersama keluarga atau rombongan sekolah. Hubungi kami via WhatsApp untuk reservasi massal atau ikuti petunjuk Google Maps di samping.
                        </p>
                    </div>

                    <div class="space-y-4 border-l-2 border-emerald-500 pl-6">
                        <p class="text-sm text-slate-500">Jam Operasional:</p>
                        <p class="text-sm font-bold text-slate-900 dark:text-white">
                            {{ \App\Models\SiteSetting::getValue('operating_days', 'Senin - Minggu') }} ({{ \App\Models\SiteSetting::getValue('operating_hours', '08:00 - 17:00 WIB') }})
                        </p>
                    </div>

                    <div>
                        <a href="https://wa.me/{{ \App\Models\SiteSetting::getValue('contact_whatsapp', '6281234567890') }}?text=Halo%20Sawah%20Pulo%20Hub,%20saya%20ingin%20bertanya%20mengenai..." target="_blank" class="inline-flex items-center gap-2 px-6 py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-2xl shadow-lg shadow-emerald-600/10 hover:shadow-emerald-600/20 transition-all">
                            Chat Admin WhatsApp Resmi
                        </a>
                    </div>
                </div>

                <!-- Google Maps Frame -->
                <div class="bg-slate-50 dark:bg-slate-800/40 p-3 rounded-3xl border border-slate-100 dark:border-slate-800/50 shadow-sm">
                    <iframe 
                        src="{{ \App\Models\SiteSetting::getValue('contact_maps_embed', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15822.428458925574!2d112.5028479!3d-7.5414969!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78e1b1b1b1b1b1%3A0x1b1b1b1b1b1b1b1b!2sMojokerto%2C%20East%20Java!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid') }}" 
                        width="100%" 
                        height="350" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        class="rounded-2xl shadow-inner border dark:border-slate-800">
                    </iframe>
                </div>

            </div>
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
