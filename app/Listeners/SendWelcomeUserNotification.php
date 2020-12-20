<?php

namespace App\Listeners;

use App\Notifications\WelcomeUser;
use Illuminate\Auth\Events\Verified;

class SendWelcomeUserNotification
{
    /**
     * history) monitored the following server error log indicating that slack api could be down.
     *
     * @param Verified $event
     */
    public function handle(Verified $event)
    {
        try {
            $event->user->notify(new WelcomeUser());
        } catch (\Throwable $e) {
            \Log::info($e->getMessage());
        }
    }
}
