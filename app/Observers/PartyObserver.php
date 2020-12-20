<?php

namespace App\Observers;

use App\Events\PartyCreated;
use App\Events\PartyDecisionMadeByFriend;
use App\Events\PartyDecisionMadeByUser;
use App\Models\Party;

class PartyObserver
{
    /**
     * Handle the party "created" event.
     *
     * @param  Party  $party
     * @return void
     */
    public function created(Party $party)
    {
        event(new PartyCreated($party));
    }

    /**
     * Handle the party "updated" event.
     *
     * @param  Party  $party
     * @return void
     */
    public function updated(Party $party)
    {
        if ($party->isDirty('user_decision')) {
            \Log::info('user_decision updated');
            event(new PartyDecisionMadeByUser($party));
        } else if ($party->isDirty('friend_decision')) {
            \Log::info('friend_decision updated');
            event(new PartyDecisionMadeByFriend($party));
        }
    }

    /**
     * Handle the party "deleted" event.
     *
     * @param  Party  $party
     * @return void
     */
    public function deleted(Party $party)
    {
        //
    }

    /**
     * Handle the party "restored" event.
     *
     * @param  Party  $party
     * @return void
     */
    public function restored(Party $party)
    {
        //
    }

    /**
     * Handle the party "force deleted" event.
     *
     * @param  Party  $party
     * @return void
     */
    public function forceDeleted(Party $party)
    {
        //
    }
}
