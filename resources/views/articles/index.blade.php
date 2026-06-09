<x-tourist-layout>
    <x-slot name="title">Artikel & Berita | Sawah Pulo Hub</x-slot>

    <!-- Header Section -->
    <section class="relative bg-slate-900 text-white overflow-hidden py-20">
        <div class="absolute inset-0 opacity-30">
            <img src="https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?auto=format&fit=crop&w=1920&q=80" alt="Artikel Sawah Pulo" class="w-full h-full object-cover object-center" />
            <div class="absolute inset-0 bg-slate-950"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight">Artikel & Berita Terbaru</h1>
            <p class="text-slate-400 text-sm sm:text-base max-w-xl mx-auto">Wawasan seputar pertanian hidroponik, budidaya tanaman organik, dan informasi agenda di Sawah Pulo Hub.</p>
        </div>
    </section>

    <!-- Articles Section -->
    <section class="py-16 bg-white dark:bg-slate-900 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="text-center max-w-2xl mx-auto mb-10 space-y-3">
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest block">Kanal Edukasi</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">Tips & Kegiatan Eduwisata</h2>
                <p class="text-slate-500 text-sm">Temukan artikel informatif terbaru yang ditulis oleh tim ahli agronomi dan manajemen kami.</p>
            </div>

            @if($articles->isEmpty())
                <div class="text-center py-20 text-slate-400">
                    <p class="text-lg">Belum ada artikel yang dipublikasikan saat ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($articles as $article)
                        <div class="bg-white dark:bg-slate-900 rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-all border border-slate-100 dark:border-slate-800 flex flex-col justify-between">
                            <div>
                                <div class="w-full h-48 bg-slate-100 dark:bg-slate-950 overflow-hidden relative">
                                    <img src="{{ $article->image_path ?? 'https://images.unsplash.com/photo-1500937386664-56d159f8e281?auto=format&fit=crop&w=500&q=80' }}" alt="{{ $article->title }}" class="w-full h-full object-cover" />
                                    <span class="absolute top-4 left-4 text-xs font-semibold text-white bg-emerald-600 px-3 py-1.5 rounded-full shadow-sm">
                                        {{ $article->category->name ?? 'Wisata' }}
                                    </span>
                                </div>
                                <div class="p-6 space-y-3">
                                    <div class="text-xs text-slate-400 flex items-center gap-1.5">
                                        <span>📅 {{ $article->created_at->format('d M Y') }}</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white text-lg line-clamp-2 leading-snug">
                                        {{ $article->title }}
                                    </h3>
                                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-3">
                                        {{ Str::limit(strip_tags($article->content), 140) }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="px-6 pb-6 pt-2">
                                <a href="{{ route('articles.show', $article->id) }}" class="inline-flex items-center gap-1.5 text-sm text-emerald-600 dark:text-emerald-400 font-semibold hover:text-emerald-500 transition-colors">
                                    Baca Selengkapnya
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination Links -->
                <div class="pt-8">
                    {{ $articles->links() }}
                </div>
            @endif

        </div>
    </section>
</x-tourist-layout>
