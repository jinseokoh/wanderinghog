<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = [];

        if ($professionType = $request->get('profession_type')) {
            $data['profession_type'] = $professionType;
        }
        if ($profession = $request->get('profession')) {
            $data['profession'] = $profession;
        }
        if ($height = $request->get('height')) {
            $data['height'] = $height;
        }
        if ($vehicle = $request->get('vehicle')) {
            $data['vehicle'] = $vehicle;
        }
        if ($smoking = $request->get('smoking')) {
            $data['smoking'] = $smoking;
        }
        if ($drinking = $request->get('drinking')) {
            $data['drinking'] = $drinking;
        }
        if ($latitude = $request->get('latitude')) {
            $data['latitude'] = $latitude;
        }
        if ($longitude = $request->get('longitude')) {
            $data['longitude'] = $longitude;
        }
        if ($intro = $request->get('intro')) {
            $data['intro'] = $intro;
        }

        if (! count($data)) {
            return response()->json([
                'error' => 'not a valid request'
            ], 422);
        }

        $user->profile->update($data);
        return response()->json([
            'data' => 'ok',
        ]);
    }
}
