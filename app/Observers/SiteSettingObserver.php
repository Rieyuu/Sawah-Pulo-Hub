<?php

namespace App\Observers;

use App\Models\SiteSetting;

class SiteSettingObserver
{
    /**
     * Handle the SiteSetting "creating" event.
     */
    public function creating(SiteSetting $siteSetting): void
    {
        if (auth()->check()) {
            $siteSetting->user_id = auth()->id();
        }
    }

    /**
     * Handle the SiteSetting "updating" event.
     */
    public function updating(SiteSetting $siteSetting): void
    {
        if (auth()->check()) {
            $siteSetting->user_id = auth()->id();
        }
    }
}
