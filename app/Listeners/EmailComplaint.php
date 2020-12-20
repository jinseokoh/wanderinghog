<?php

namespace App\Listeners;

use App\Models\User;
use jdavidbakr\MailTracker\Events\ComplaintMessageEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EmailComplaint
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
     * @param  ComplaintMessageEvent  $event
     * @return void
     */
    public function handle(ComplaintMessageEvent $event)
    {
        \Log::info('[INFO] complaints detected');

        $email = $event->email_address;

        try {
            $user = User::where('email', $email)->firstOrFail();
            $user->profile->marketing = [false, optional($user->profile)->marketing[1]];
            $user->profile->save();

            // todo. notify user to describe what just happened

        } catch (\Exception $e) {
            // slack notification
            \Log::info("[AMAZON] SES email to ({$email}) flagged as spam but, could not find the user.");
        }
    }
}
