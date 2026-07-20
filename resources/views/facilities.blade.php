<x-tourist-layout>
    <x-slot name="title">Fasilitas Eduwisata | Sawah Pulo Hub</x-slot>

    <!-- Header Section -->
    <section class="relative bg-slate-900 text-white overflow-hidden py-20">
        <div class="absolute inset-0 opacity-30">
            <img src="{{ asset('images/sawah_pulo_background.png') }}" alt="Fasilitas Sawah Pulo" class="w-full h-full object-cover object-center" />
            <div class="absolute inset-0 bg-slate-950"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight">Peta & Fasilitas Wisata</h1>
            <p class="text-slate-400 text-sm sm:text-base max-w-xl mx-auto">Lihat tata letak peta denah kawasan wisata dan rincian fasilitas unggulan yang kami sediakan.</p>
        </div>
    </section>

    <!-- Peta Denah Section -->
    <section class="py-16 bg-white dark:bg-slate-900 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-50 dark:bg-slate-800/40 shadow-xl shadow-slate-200/60 dark:shadow-none hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-emerald-900/10 hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300 border border-emerald-100 dark:border-emerald-900/30 rounded-3xl p-6 sm:p-10">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-10 items-center">
                    
                    <!-- Info Denah (2 cols) -->
                    <div class="lg:col-span-2 space-y-6">
                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest block">Layout Area</span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white leading-tight">Denah Peta 2D Kawasan</h2>
                        <p class="text-slate-600 font-medium dark:text-slate-300 text-sm sm:text-base leading-relaxed">
                            Denah 2D ini menunjukkan seluruh area Eduwisata Sawah Pulo Farm secara lengkap. Anda dapat melihat koordinat spot-spot menarik seperti area perkebunan tradisional, jembatan pandang, peternakan kelinci/kambing, dan kawasan kuliner.
                        </p>
                        <div class="text-xs text-slate-400">
                            💡 Klik gambar denah di samping untuk memperbesar secara penuh.
                        </div>
                    </div>

                    <!-- Image Plan (3 cols) -->
                    <div class="lg:col-span-3 bg-white dark:bg-slate-950 p-2.5 rounded-2xl border border-emerald-100 dark:border-emerald-900/30 cursor-zoom-in" x-data="{ showModal: false }">
                        <img @click="showModal = true" src="{{ \App\Models\SiteSetting::getValue('site_plan_image', 'https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=1200&q=80') }}" alt="2D Site Plan" class="w-full h-auto rounded-xl object-cover hover:opacity-95 transition-opacity" />

                        <!-- Zoom Modal (pannable) -->
                        <template x-teleport="body">
                            <div x-show="showModal"
                                x-effect="showModal ? document.body.style.overflow='hidden' : document.body.style.overflow=''"
                                @click="showModal = false"
                                @keydown.escape.window="showModal = false"
                                class="fixed inset-0 z-50 overflow-auto bg-slate-950/90 backdrop-blur-sm cursor-zoom-out"
                                x-cloak>
                                <div class="flex items-center justify-center min-h-full min-w-full p-8">
                                    <div @click.stop class="relative cursor-default">
                                        <p class="text-center text-white/40 text-xs mb-3">Klik area gelap untuk menutup</p>
                                        <img src="{{ \App\Models\SiteSetting::getValue('site_plan_image', 'https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=1200&q=80') }}"
                                            alt="2D Site Plan Full"
                                            style="display:block; width:85vw; max-width:1100px; height:auto;"
                                            class="rounded-2xl shadow-2xl mx-auto" />
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Rincian Fasilitas Section -->
    <section class="py-16 bg-slate-50 dark:bg-slate-950 border-t border-emerald-100 dark:border-slate-900 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest block">Daftar Rincian</span>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white">Fasilitas Lengkap Destinasi</h2>
                <p class="text-slate-600 font-medium dark:text-slate-300 text-sm sm:text-base">Jelajahi setiap fasilitas menarik yang siap melengkapi momen rekreasi dan pembelajaran Anda.</p>
            </div>

            @php
                $facilities = \App\Models\Facility::all();
            @endphp

            @if($facilities->isEmpty())
                <div class="text-center py-20 text-slate-400">
                    <p class="text-lg">Belum ada rincian fasilitas terdaftar.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($facilities as $facility)
                        <div class="bg-white dark:bg-slate-900 border border-emerald-100 dark:border-emerald-900/30 rounded-3xl overflow-hidden group hover:-translate-y-2 hover:shadow-2xl hover:shadow-emerald-900/10 hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300 cursor-pointer flex flex-col justify-between">
                            <div>
                                <div class="w-full h-52 bg-slate-100 dark:bg-slate-950 overflow-hidden relative">
                                    <img src="{{ $facility->image_path ? asset($facility->image_path) : 'https://images.unsplash.com/photo-1500937386664-56d159f8e281?auto=format&fit=crop&w=500&q=80' }}" alt="{{ $facility->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out" />
                                </div>
                                <div class="p-6 space-y-3">
                                    <h3 class="font-bold text-slate-900 dark:text-white text-lg">{{ $facility->name }}</h3>
                                    <p class="text-sm text-slate-600 font-medium dark:text-slate-300 leading-relaxed">{{ $facility->description }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</x-tourist-layout>
