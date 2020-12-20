<?php

namespace App\Http\Controllers\Users;

use App\Handlers\AwsSnsPushHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function upsert(AwsSnsPushHandler $handler, Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string', // random string from FCM
            'platform' => 'required|string', // either `android` or `ios`
        ]);

        $token = $request->input('token');
        $platform = $request->input('platform');
        $user = $request->user();

        try {
            $handler->upsert($user, $token, $platform);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        }

        return response()->json([
            'data' => 'ok',
        ]);
    }
}
