<?php

namespace App\Events;

use App\Models\Appointment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VenueDeselected
{
    use Dispatchable, SerializesModels;

    public int $venue_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $venue_id)
    {
        $this->venue_id = $venue_id;
    }
}
