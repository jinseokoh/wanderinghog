<?php

namespace App\Listeners;

use App\Models\User;
use jdavidbakr\MailTracker\Events\PermanentBouncedMessageEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EmailBounced
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
     * @param  PermanentBouncedMessageEvent  $event
     * @return void
     */
    public function handle(PermanentBouncedMessageEvent $event)
    {
        \Log::info('[INFO] bounces detected');

        $email = $event->email_address;

        try {
            $user = User::where('email', $email)->firstOrFail();
            $user->email_verified_at = null;
            $user->save();

            // todo. notify user to describe what just happened

        } catch (\Exception $e) {
            // slack notification
            \Log::info("[AMAZON] SES email to ({$email}) bounced but, could not find the user.");
        }
    }
}
