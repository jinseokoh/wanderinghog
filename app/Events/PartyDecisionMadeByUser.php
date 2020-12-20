<?php

namespace App\Events;

use App\Models\Party;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PartyDecisionMadeByUser
{
    use Dispatchable, SerializesModels;

    /**
     * @var Party
     */
    public Party $party;

    /**
     * Create a new event instance.
     *
     * @param Party $party
     */
    public function __construct(Party $party)
    {
        $this->party = $party;
    }
}
