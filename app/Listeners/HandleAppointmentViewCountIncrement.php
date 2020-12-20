<?php

namespace App\Listeners;

use App\Events\AppointmentViewed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleAppointmentViewCountIncrement implements ShouldQueue
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
     * TTL of 1 hour, which means the view count will increase once every 1 hour.
     *
     * @param  AppointmentViewed  $event
     * @return void
     */
    public function handle(AppointmentViewed $event)
    {
        if (! \Cache::has($event->cacheKey)) {
            $event->appointment->increment('view_count');
            \Cache::put($event->cacheKey, true, 60 * 60);
        }
    }
}
