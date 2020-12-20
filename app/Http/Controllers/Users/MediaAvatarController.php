<?php

namespace App\Http\Controllers\Users;

use App\Handlers\GoogleVisionHandler;
use App\Handlers\MediaHandler;
use App\Http\Controllers\Controller;
use App\Http\Resources\MediaResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaAvatarController extends Controller
{
    /**
     * @param Request $request
     * @return MediaResource|JsonResponse
     */
    public function store(
        Request $request,
        MediaHandler $mediaHandler,
        GoogleVisionHandler $googleVisionHandler
    ) {
        $request->validate([
            'image' => ['required', 'image'],
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

        try {
            $mediaHandler->saveAvatarFromUploadedFile($user, $uploadedFile);
        } catch (\Throwable $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 422);
        }

        $media = $user->refresh()->getMedia('avatars')->first();

        return new MediaResource($media);
    }
}
