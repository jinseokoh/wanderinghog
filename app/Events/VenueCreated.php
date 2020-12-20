<?php

namespace App\Events;

use App\Models\Venue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VenueCreated
{
    use Dispatchable, SerializesModels;

    /**
     * @var Venue
     */
    public Venue $venue;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Venue $venue)
    {
        $this->venue = $venue;
    }
}
