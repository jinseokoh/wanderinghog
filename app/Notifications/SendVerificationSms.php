<?php

namespace App\Notifications;

use App\Handlers\OtpHandler;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use JinseokOh\Aligo\AligoTextChannel;
use JinseokOh\Aligo\AligoTextMessage;
use NotificationChannels\AwsSns\SnsChannel;
use NotificationChannels\AwsSns\SnsMessage;
use Seungmun\Sens\Sms\SmsChannel;
use Seungmun\Sens\Sms\SmsMessage;

class SendVerificationSms extends Notification implements ShouldQueue
{
    use Queueable;

//    /**
//     * Get the notification's delivery channels.
//     *
//     * @param  mixed  $notifiable
//     * @return array
//     */
//    public function via($notifiable)
//    {
//        return [AligoKakaoChannel::class];
//    }
//
//    public function toAligoKakao($notifiable)
//    {
//        $otp = (new OtpHandler())
//            ->generateEmailOtp('phone', $notifiable);
//
//        return (new AligoKakaoMessage())
//            ->code("TB_0824")
//            ->replacements([$otp]);
//    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if (app()->environment() === 'local') {
            \Log::info('SMS via Amazon');
            return [SmsChannel::class];
        }

        \Log::info('SMS via Aligo');
        return [AligoTextChannel::class];
    }

    public function toAligoText($notifiable)
    {
        $otp = (new OtpHandler())
            ->generateEmailOtp('phone', $notifiable);

        return (new AligoTextMessage())
            ->content("본인인증 번호 : $otp");
    }

    /**
     * via NCloud SENS
     * @param $notifiable
     * @return SmsMessage
     */
    public function toSms($notifiable)
    {
        $otp = (new OtpHandler())
            ->generateEmailOtp('phone', $notifiable);

        return (new SmsMessage)
            ->type('SMS')
            ->to($notifiable->phone)
            ->from('02-719-6810')
            ->content("본인인증 번호 : $otp");
    }

    /**
     * via AWS SNS
     * @param $notifiable
     * @return SnsMessage
     */
    public function toSns($notifiable)
    {
        $otp = (new OtpHandler())
            ->generateEmailOtp('phone', $notifiable);

        return SnsMessage::create()
            ->body("본인인증 번호 : $otp")
            ->sender('02-719-6810');
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
