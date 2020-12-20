<?php

namespace App\Listeners;

use App\Events\VenueSelected;
use App\Handlers\RegionHandler;
use App\Models\Venue;
use App\Support\RegionDetector;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleVenueSelection implements ShouldQueue
{
    /**
     * @var RegionDetector
     */
    private RegionDetector $regionDetector;
    /**
     * @var RegionHandler
     */
    private RegionHandler $regionHandler;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        RegionDetector $regionDetector,
        RegionHandler $regionHandler
    ) {
        //
        $this->regionDetector = $regionDetector;
        $this->regionHandler = $regionHandler;
    }

    /**
     * Handle the event.
     *
     * @param  VenueSelected  $event
     * @return void
     */
    public function handle(VenueSelected $event)
    {
        $appointment = $event->appointment;
        $venue = Venue::find($appointment->venue_id);

        if ($venue) {
            $address = $venue->address;
            $latitude = $venue->latitude;
            $longitude = $venue->longitude;
            $slug = $this->regionDetector->detect($address, $latitude, $longitude);
            $regionIds = $this->regionHandler->getFamilyRegionIds($slug);

            // sync regions associated with appointment
            $appointment->regions()->sync($regionIds);
            $venue->increment('usage_count');
        }
    }
}
