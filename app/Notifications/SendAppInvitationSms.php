<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use JinseokOh\Aligo\AligoKakaoChannel;
use JinseokOh\Aligo\AligoKakaoMessage;
use JinseokOh\Aligo\AligoTextChannel;
use JinseokOh\Aligo\AligoTextMessage;
// use Nubix\Notifications\Messages\SmsMessage;

class SendAppInvitationSms extends Notification implements ShouldQueue
{
    use Queueable;

    public $name;

    public function __construct(User $user)
    {
        $this->name = $user ? $user->name : '바연생 회원';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [AligoTextChannel::class];
        // return ['sms'];
    }

    /**
     * @param $notifiable
     * @return AligoTextMessage
     */
    public function toAligoText($notifiable)
    {
        return (new AligoTextMessage())
            ->content("{$this->name}님이 친구신청을 보냈습니다. 앱스토어를 통하여 [바른연애생활]을 설치해주세요.");
    }

    /**
     * @param $notifiable
     * @return AligoKakaoMessage
     */
    public function toAligoKakao($notifiable)
    {
        return (new AligoKakaoMessage())
            ->code('TB_0824')
            ->replacements(['xxx', 'yyy']);
    }

    /**
     * @param $notifiable
     */
    public function toSms($notifiable)
    {
        return (new SmsMessage())
            ->content("{$this->name}님이 친구신청을 보냈습니다. 앱스토어를 통하여 [바른연애생활]을 설치해주세요.");
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
