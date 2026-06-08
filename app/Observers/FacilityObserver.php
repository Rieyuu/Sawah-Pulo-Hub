<?php

namespace App\Observers;

use App\Models\Facility;

class FacilityObserver
{
    /**
     * Handle the Facility "creating" event.
     */
    public function creating(Facility $facility): void
    {
        if (auth()->check()) {
            $facility->user_id = auth()->id();
        }
    }

    /**
     * Handle the Facility "updating" event.
     */
    public function updating(Facility $facility): void
    {
        if (auth()->check()) {
            $facility->user_id = auth()->id();
        }
    }
}
