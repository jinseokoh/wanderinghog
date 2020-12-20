<?php

namespace App\Notifications;

use App\Handlers\OtpHandler;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as Notification;
use Illuminate\Support\Facades\Lang;

class ResetPassword extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $otp = (new OtpHandler())->generateEmailOtp('reset', $notifiable);

        return (new MailMessage)
            ->subject(Lang::get('Reset your password.'))
            ->markdown('mail.auth.reset', ['otp' => $otp]);
    }
}
