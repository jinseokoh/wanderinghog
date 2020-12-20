<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Multicaret\Acquaintances\Status;

class FriendshipController extends Controller
{
    public function send(Request $request)
    {
        $user = $request->user();
        $recipientId = $request->get('recipient_id');
        $recipient = User::findOrFail($recipientId);

        $response = $user->befriend($recipient);
        if (! $response) {
            // mutual friend requests result in automatic acceptance
            $friendship = $user->getFriendship($recipient);
            if (optional($friendship)->status === Status::PENDING &&
                optional($friendship)->recipient_id === $user->id
            ) {
                $friendship->update([
                    'status' => Status::ACCEPTED
                ]);
                return response()->json([
                    'data' => 'ok',
                ], 201);
            }
            // tried but couldn't go through
            return response()->json([
                'data' => 'ignored',
            ], 200);
        }

        return response()->json([
            'data' => 'ok',
        ], 201);
    }

    public function accept(Request $request)
    {
        $user = $request->user();
        $senderId = $request->get('sender_id');
        $sender = User::findOrFail($senderId);

        try {
            $user->acceptFriendRequest($sender);
        } catch (\Throwable $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
            ], 422);
        }

        return response()->json([
            'data' => 'ok',
        ]);
    }

    public function deny(Request $request)
    {
        $user = $request->user();
        $senderId = $request->get('sender_id');
        $sender = User::findOrFail($senderId);

        try {
            $user->denyFriendRequest($sender);
        } catch (\Throwable $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
            ], 422);
        }

        return response()->json([
            'data' => 'ok',
        ]);
    }

    public function remove(Request $request)
    {
        $user = $request->user();
        $userId = $request->get('user_id');
        $friend = User::findOrFail($userId);

        try {
            $user->unfriend($friend);
        } catch (\Throwable $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
            ], 422);
        }

        return response()->json([
            'data' => 'ok',
        ]);
    }

    public function block(Request $request)
    {
        $user = $request->user();
        $userId = $request->get('user_id');
        $friend = User::findOrFail($userId);

        try {
            $user->blockFriend($friend);
        } catch (\Throwable $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
            ], 422);
        }

        return response()->json([
            'data' => 'ok',
        ]);
    }

    public function unblock(Request $request)
    {
        $user = $request->user();
        $userId = $request->get('user_id');
        $friend = User::findOrFail($userId);

        try {
            $user->unblockFriend($friend);
        } catch (\Throwable $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
            ], 422);
        }

        return response()->json([
            'data' => 'ok',
        ]);
    }
}
