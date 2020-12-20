<?php

namespace App\Http\Controllers\Users;

use App\Events\UserActivated;
use App\Handlers\GoogleVisionHandler;
use App\Handlers\MediaHandler;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BasicInfoController extends Controller
{
    /**
     * @param Request $request
     * @return UserResource|JsonResponse
     * @throws \Throwable
     */
    public function store(
        Request $request,
        MediaHandler $mediaHandler,
        GoogleVisionHandler $googleVisionHandler
    ) {
        $request->validate([
            'username' => [ 'string', 'max:24', 'unique:users' ], // costs a coin
            'image' => ['required', 'image'],
            'profession_type' => ['required', 'integer'],
            'smoking' => ['required', 'integer'],
            'drinking' => ['required', 'integer'],
        ]);

        $user = $request->user();
        $uploadedFile = $request->file('image');
        $contents = file_get_contents($uploadedFile->getRealPath());

        try {
            $googleVisionHandler->detectSingleFace($contents);
        } catch (\Throwable $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 422);
        }

        \DB::beginTransaction();
        try {
            $mediaHandler->saveAvatarFromUploadedFile($user, $uploadedFile);
            $user->update([
                'username' => $request->get('username'),
                'is_active' => true,
            ]);
            $user->profile->update([
                'profession_type' => $request->get('profession_type'),
                'smoking' => $request->get('smoking'),
                'drinking' => $request->get('drinking'),
            ]);
            \DB::commit();
        } catch (\Throwable $exception) {
            \DB::rollBack();
            \Log::error($exception->getMessage());

            return response()->json([
                'error' => $exception->getMessage()
            ], 422);
        }

        $updatedUser = $user->refresh();

        event(new UserActivated($updatedUser));

        return new UserResource($updatedUser);
    }
}
