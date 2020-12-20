<?php

namespace App\Http\Controllers\Rooms;

use App\Events\RoomMessageSent;
use App\Events\PublicMessageSent;
use App\Events\RoomPresenceMessageSent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * @param Request $request
     */
    public function index(int $id, Request $request)
    {
        $data = [
            [
                'type' => 'text',
                'user_id' => 1,
                'data' => '안녕 난 영희야.',
            ],
            [
                'type' => 'text',
                'user_id' => 2,
                'data' => '안녕 난 희숙이야.',
            ],
            [
                'type' => 'text',
                'user_id' => 3,
                'data' => '안녕 난 철수야.',
            ],
            [
                'type' => 'text',
                'user_id' => 11,
                'data' => '방가방가 내 이름은 승리야.',
            ],
            [
                'type' => 'text',
                'user_id' => 1,
                'data' => '빵에서 벌써 나왔니?',
            ],
        ];

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function broadcast(Request $request)
    {
        $message = $request->get('message');

        broadcast(new PublicMessageSent($message));

        return response()->json(['message' => 'ok']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chat(int $room, Request $request)
    {
        $user = $request->user();
        $message = $request->get('message');

        $payload = [
            'type' => 'text',
            'user_id' => $user->id,
            'data' => $message,
        ];

        broadcast(new RoomMessageSent($room, $payload));//->toOthers();

        return response()->json(['message' => 'ok']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function presence(int $room, Request $request)
    {
        $user = $request->user();

        $payload = [
            'type' => 'presence',
            'data' => 'let me join you.',
        ];

        broadcast(new RoomPresenceMessageSent($room, $payload));//->toOthers();

        return response()->json(['message' => 'ok']);
    }
}
