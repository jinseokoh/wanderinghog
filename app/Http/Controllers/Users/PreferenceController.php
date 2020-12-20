<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreferenceUpdateRequest;
use Illuminate\Http\JsonResponse;

class PreferenceController extends Controller
{
    public function update(PreferenceUpdateRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = [];

        if ($ready = $request->get('ready')) {
            $data['ready'] = $ready;
        }
        if ($height = $request->get('height')) {
            $data['min_height'] = $height['min'];
            $data['max_height'] = $height['max'];
        }
        if ($age = $request->get('age')) {
            $data['min_age'] = $age['min'];
            $data['max_age'] = $age['max'];
        }
        if ($gender = $request->get('gender')) {
            $data['gender'] = strtoupper($gender);
        }
        if ($smoking = $request->get('smoking')) {
            $data['smoking'] = $smoking;
        }
        if ($drinking = $request->get('drinking')) {
            $data['drinking'] = $drinking;
        }
        if ($notifications = $request->get('notifications')) {
            $data['notifications'] = $notifications;
        }

        if (! count($data)) {
            return response()->json([
                'error' => 'not a valid request'
            ], 422);
        }

        $user->preference->update($data);
        return response()->json([
            'data' => 'ok',
        ]);
    }
}
