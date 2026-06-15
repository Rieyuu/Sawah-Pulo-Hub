<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Facility;
use App\Models\SiteSetting;
use App\Models\Ticket;
use App\Observers\ArticleObserver;
use App\Observers\FacilityObserver;
use App\Observers\SiteSettingObserver;
use App\Observers\TicketObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Ticket::observe(TicketObserver::class);
        Facility::observe(FacilityObserver::class);
        SiteSetting::observe(SiteSettingObserver::class);
        Article::observe(ArticleObserver::class);
    }
}
