<?php

namespace App\Events;

use App\Models\Party;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PartyCreated
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
