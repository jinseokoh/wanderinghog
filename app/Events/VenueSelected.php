<?php

namespace App\Events;

use App\Models\Appointment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VenueSelected
{
    use Dispatchable, SerializesModels;

    public Appointment $appointment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }
}
