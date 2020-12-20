<?php

namespace App\Listeners;

use App\Events\VenueDeselected;
use App\Models\Venue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleVenueDeselection implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  VenueDeselected  $event
     * @return void
     */
    public function handle(VenueDeselected $event)
    {
        $venue = Venue::find($event->venue_id);

        if ($venue) {
            $venue->decrement('usage_count');
        }
    }
}
