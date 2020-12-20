<?php

namespace App\Listeners;

use jdavidbakr\MailTracker\Events\EmailDeliveredEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EmailDelivered
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
     * @param  EmailDeliveredEvent  $event
     * @return void
     */
    public function handle(EmailDeliveredEvent $event)
    {
        $email = $event->email_address;
    }
}
