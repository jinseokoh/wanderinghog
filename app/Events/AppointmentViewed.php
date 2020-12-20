<?php

namespace App\Events;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentViewed
{
    use Dispatchable, SerializesModels;

    public $appointment;
    public $cacheKey;

    /**
     * Create a new event instance.
     *
     * @param Appointment|Model $appointment
     * @param string $cacheKey
     */
    public function __construct(Appointment $appointment, string $cacheKey)
    {
        $this->appointment = $appointment;
        $this->cacheKey = $cacheKey;
    }
}
