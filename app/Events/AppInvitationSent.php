<?php

namespace App\Events;

use App\Models\AppInvitation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppInvitationSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var AppInvitation
     */
    public AppInvitation $appInvitation;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AppInvitation $appInvitation)
    {
        $this->appInvitation = $appInvitation;
    }
}
