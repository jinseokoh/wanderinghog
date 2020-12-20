<?php

namespace App\Listeners;

use App\Events\AppInvitationSent;
use App\Models\AppInvitation;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleAppInvitation implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(AppInvitationSent $event)
    {
        /** @var AppInvitation $appInvitation */
        $appInvitation = $event->appInvitation;
        $userId = $appInvitation->user_id;
        $phone = $appInvitation->phone;
        $user = User::find($userId);

        // In case any active user exists with the phone number, send $recipient a friend request
        $recipient = User::where('phone', $phone)
            ->where('is_active', true)
            ->whereNotNull('phone_verified_at')
            ->first();

        if ($recipient) {
            $user->befriend($recipient);
            // Todo. send notification to the recipient like "you've got a friend request from $user"
        }
    }
}
