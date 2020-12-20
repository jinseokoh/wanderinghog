<?php

namespace App\Observers;

use App\Events\VenueCreated;
use App\Models\Venue;

class VenueObserver
{
    /**
     * Handle the venue "created" event.
     *
     * @param  Venue  $venue
     * @return void
     */
    public function created(Venue $venue)
    {
        event(new VenueCreated($venue));
    }

    /**
     * Handle the venue "updated" event.
     *
     * @param  Venue  $venue
     * @return void
     */
    public function updated(Venue $venue)
    {
        //
    }

    /**
     * Handle the venue "deleted" event.
     *
     * @param  Venue  $venue
     * @return void
     */
    public function deleted(Venue $venue)
    {
        //
    }

    /**
     * Handle the venue "restored" event.
     *
     * @param  Venue  $venue
     * @return void
     */
    public function restored(Venue $venue)
    {
        //
    }

    /**
     * Handle the venue "force deleted" event.
     *
     * @param  Venue  $venue
     * @return void
     */
    public function forceDeleted(Venue $venue)
    {
        //
    }
}
