<x-tourist-layout>
    <x-slot name="title">Profil Wisata | Sawah Pulo Hub</x-slot>

    <!-- Header Section -->
    <section class="relative bg-slate-900 text-white overflow-hidden py-20">
        <div class="absolute inset-0 opacity-30">
            <img src="{{ asset('images/sawah_pulo_background.png') }}" alt="Sawah Pulo" class="w-full h-full object-cover object-center" />
            <div class="absolute inset-0 bg-slate-950"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight">Profil Wisata</h1>
            <p class="text-slate-400 text-sm sm:text-base max-w-xl mx-auto">Mengenal visi, misi, sejarah, dan tata kelola struktur organisasi eduwisata Sawah Pulo.</p>
        </div>
    </section>

    <!-- Main Content Section -->
    <section class="py-20 bg-white dark:bg-slate-900 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-20">
            
            <!-- History / About -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                <div class="space-y-6">
                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest block">Sejarah & Profil</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white leading-tight">
                        Tentang Sawah Pulo Farm
                    </h2>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-sm sm:text-base whitespace-pre-line">
                        {{ \App\Models\SiteSetting::getValue('about_history', 'Sawah Pulo Farm didirikan sebagai kawasan eduwisata pertanian modern terpadu yang memadukan keindahan alam pedesaan dengan metode agribisnis berkelanjutan.') }}
                    </p>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800 p-4 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm">
                    <img src="{{ \App\Models\SiteSetting::getValue('about_image', asset('images/sawah_pulo_background.png')) }}" alt="Sawah Pulo" class="w-full h-auto rounded-2xl object-cover" />
                </div>
            </div>

            <!-- Vision and Mission -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Visi -->
                <div class="p-8 bg-slate-50 dark:bg-slate-800/40 rounded-3xl space-y-4 border border-slate-100/50 dark:border-slate-800/50">
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-950/50 rounded-2xl flex items-center justify-center text-2xl">
                        🎯
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Visi Kami</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed italic">
                        "{{ \App\Models\SiteSetting::getValue('about_vision', 'Menjadi destinasi agrowisata edukatif terkemuka yang melestarikan alam.') }}"
                    </p>
                </div>

                <!-- Misi -->
                <div class="p-8 bg-slate-50 dark:bg-slate-800/40 rounded-3xl space-y-4 border border-slate-100/50 dark:border-slate-800/50">
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-950/50 rounded-2xl flex items-center justify-center text-2xl">
                        🚀
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Misi Kami</h3>
                    <div class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed whitespace-pre-line">
                        {{ \App\Models\SiteSetting::getValue('about_mission', "1. Edukasi masyarakat tentang pertanian modern.\n2. Mengembangkan pariwisata ramah lingkungan.") }}
                    </div>
                </div>
            </div>

            <!-- Structure Organization -->
            <div class="space-y-6 text-center max-w-4xl mx-auto pt-8 border-t border-slate-100 dark:border-slate-800">
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest block">Struktur Manajemen</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">Struktur Organisasi</h2>
                <p class="text-slate-500 text-sm max-w-lg mx-auto">Berikut adalah susunan komite dan kepengurusan pengelola Eduwisata Sawah Pulo Farm.</p>
                
                <div class="bg-slate-50 dark:bg-slate-800/40 p-4 rounded-3xl border border-slate-100 dark:border-slate-800/50 shadow-inner max-w-2xl mx-auto">
                    <img src="{{ \App\Models\SiteSetting::getValue('about_structure_image', 'https://images.unsplash.com/photo-1531538606174-0f90ff5dce83?auto=format&fit=crop&w=800&q=80') }}" alt="Struktur Organisasi" class="w-full h-auto rounded-2xl object-cover mx-auto" />
                </div>
            </div>

        </div>
    </section>
</x-tourist-layout>
