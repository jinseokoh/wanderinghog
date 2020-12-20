<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\DatabaseNotification as Notification;

class NotificationController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();

        return NotificationResource::collection($user->notifications);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function count($userId, Request $request): JsonResponse
    {
        $user = $request->user();
        $count = $user ? $user->unreadNotifications->count() : 0;

        return response()->json([
            'data' => [
                'count' => $count
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function read(int $userId, string $noficationId, Request $request): JsonResponse
    {
        $user = $request->user();

        $notification = Notification::find($noficationId);
        if ($notification) {
            $notification->markAsRead();
            return response()->json([
                'data' => [
                    'count' => $user->unreadNotifications->count()
                ]
            ]);
        }

        return response()->json([
            'data' => [
                'message' => 'unprocessable entity'
            ]
        ], 422);
    }
}
