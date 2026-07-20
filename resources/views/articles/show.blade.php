<x-tourist-layout>
    <x-slot name="title">{{ $article->title }} | Sawah Pulo Hub</x-slot>

    <!-- Header / Banner Section -->
    <section class="py-12 bg-slate-50 dark:bg-slate-950 border-b border-emerald-100 dark:border-emerald-900/30 transition-colors">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            
            <!-- Breadcrumbs -->
            <nav class="flex text-xs sm:text-sm text-slate-400 gap-2">
                <a href="{{ route('home') }}" class="hover:text-emerald-600">Beranda</a>
                <span>/</span>
                <a href="{{ route('articles.index') }}" class="hover:text-emerald-600">Artikel</a>
                <span>/</span>
                <span class="text-slate-600 dark:text-slate-200 truncate">{{ $article->title }}</span>
            </nav>

            <span class="inline-block text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-3 py-1.5 rounded-full">
                {{ $article->category->name ?? 'Wisata' }}
            </span>
            
            <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white leading-tight">
                {{ $article->title }}
            </h1>

            <div class="flex items-center gap-4 text-xs sm:text-sm text-slate-400 pt-2 border-t border-emerald-100 dark:border-emerald-900/30">
                <span>📅 Dipublikasikan pada {{ $article->created_at->format('d M Y') }}</span>
                <span>•</span>
                <span>✍️ Oleh Admin</span>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <section class="py-12 bg-white dark:bg-slate-900 transition-colors">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">
                
                <!-- Main Image -->
                @if($article->image_path)
                    <div class="w-full h-80 sm:h-96 rounded-3xl overflow-hidden shadow-xl shadow-slate-200/60 dark:shadow-none hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-emerald-900/10 hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300 border border-emerald-100 dark:border-emerald-900/30">
                        <img src="{{ asset($article->image_path) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out" />
                    </div>
                @endif

                <!-- Content Text -->
                <article class="prose dark:prose-invert max-w-none text-slate-700 dark:text-slate-300 leading-relaxed text-sm sm:text-base whitespace-pre-line">
                    {!! nl2br(e($article->content)) !!}
                </article>

                <!-- Footer Actions -->
                <div class="pt-8 border-t border-emerald-100 dark:border-emerald-900/30 flex justify-between items-center">
                    <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs sm:text-sm font-semibold rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Artikel
                    </a>
                </div>

            </div>
        </div>
    </section>
</x-tourist-layout>
