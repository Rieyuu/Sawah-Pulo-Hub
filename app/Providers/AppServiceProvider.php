<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Ticket;
use App\Observers\TicketObserver;
use App\Models\Facility;
use App\Observers\FacilityObserver;
use App\Models\SiteSetting;
use App\Observers\SiteSettingObserver;
use App\Models\Article;
use App\Observers\ArticleObserver;

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
