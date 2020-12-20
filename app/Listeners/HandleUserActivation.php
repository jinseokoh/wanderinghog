<?php

namespace App\Listeners;

use App\Events\UserActivated;
use App\Models\AppInvitation;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Multicaret\Acquaintances\Models\Friendship;

class HandleUserActivation implements ShouldQueue
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
    public function handle(UserActivated $event)
    {
        /** @var User $user */
        $user = $event->user;
        $appInvitation = AppInvitation::where('phone', $user->phone)->first();

        if ($appInvitation) {
            // 1) send notification to $appInvitation->user_id
            // "당신이 초대한 user->name 님이 사용자명 [user->username] 으로 가입 완료했습니다."

            // 2) automatically make a new friendship record between them
            Friendship::create([
                'sender_type' => 'App\\Models\\User',
                'sender_id' => $appInvitation->user_id,
                'recipient_type' => 'App\\Models\\User',
                'recipient_id' => $user->id,
                'status' => 'accepted'
            ]);
        }
    }
}
