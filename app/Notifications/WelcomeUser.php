<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail as Notification;

class WelcomeUser extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail', 'slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(Lang::get('Thank you for joining us.'))
            ->markdown('mail.auth.welcome');
    }

    /*
     * @param  mixed  $notifiable
     * @return mixed
     */
    public function toSlack($notifiable)
    {
        $email = $notifiable->email;

        return (new SlackMessage)
            ->from('Ghost', ':ghost:')
            ->to('#application')
            ->content("🎉 {$email} 님이 회원 등록했습니다.");
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [];
    }
}
