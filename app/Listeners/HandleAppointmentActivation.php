<?php

namespace App\Listeners;

use App\Events\PartyCreated;
use App\Events\PartyDecisionMadeByUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleAppointmentActivation implements ShouldQueue
{
    /**
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param  PartyDecisionMadeByUser  $event
     * @return void
     */
    public function handle(PartyCreated $event)
    {
        $party = $event->party;
        $party->appointment->update([
            'is_active' => true,
        ]);

        \Log::info('appointment has been activated.');
    }
}
