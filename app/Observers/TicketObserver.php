<?php

namespace App\Observers;

use App\Models\Ticket;

class TicketObserver
{
    /**
     * Handle the Ticket "creating" event.
     */
    public function creating(Ticket $ticket): void
    {
        if (auth()->check()) {
            $ticket->user_id = auth()->id();
        }
    }

    /**
     * Handle the Ticket "updating" event.
     */
    public function updating(Ticket $ticket): void
    {
        if (auth()->check()) {
            $ticket->user_id = auth()->id();
        }
    }
}
