<?php

namespace App\Observers;

use App\Events\VenueDeselected;
use App\Events\VenueSelected;
use App\Models\Appointment;

class AppointmentObserver
{
    /**
     * Handle the appointment "created" event.
     *
     * @param  Appointment  $appointment
     * @return void
     */
    public function created(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the appointment "updated" event.
     *
     * @param  Appointment  $appointment
     * @return void
     */
    public function updated(Appointment $appointment)
    {
        if ($appointment->isDirty('venue_id')) {
            event(new VenueDeselected($appointment->getOriginal('venue_id')));
            event(new VenueSelected($appointment));
        }
    }

    /**
     * Handle the appointment "deleted" event.
     *
     * @param  Appointment  $appointment
     * @return void
     */
    public function deleted(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the appointment "restored" event.
     *
     * @param  Appointment  $appointment
     * @return void
     */
    public function restored(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the appointment "force deleted" event.
     *
     * @param  Appointment  $appointment
     * @return void
     */
    public function forceDeleted(Appointment $appointment)
    {
        //
    }
}
