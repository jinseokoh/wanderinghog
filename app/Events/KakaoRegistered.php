<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class KakaoRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var User
     */
    public User $user;
    public string $providerId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, string $providerId)
    {
        //
        $this->user = $user;
        $this->providerId = $providerId;
    }
}
