<?php

namespace App\Listeners;


use App\Events\KakaoRegistered;
use App\Models\Kakao;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleKakaoRegistration implements ShouldQueue
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
    public function handle(KakaoRegistered $event)
    {
        $user = $event->user;
        $kakaoId = $event->providerId;

        $kakao = Kakao::where('kakao_id', $kakaoId)->first();

        if ($kakao) {
            foreach ($kakao->users as $friend) {
                $friend->befriend($user);
            }
        }
    }
}
