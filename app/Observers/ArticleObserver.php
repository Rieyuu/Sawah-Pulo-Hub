<?php

namespace App\Observers;

use App\Models\Article;

class ArticleObserver
{
    /**
     * Handle the Article "creating" event.
     */
    public function creating(Article $article): void
    {
        if (auth()->check()) {
            $article->author_id = auth()->id();
        }
    }

    /**
     * Handle the Article "updating" event.
     */
    public function updating(Article $article): void
    {
        if (auth()->check()) {
            $article->author_id = auth()->id();
        }
    }
}
