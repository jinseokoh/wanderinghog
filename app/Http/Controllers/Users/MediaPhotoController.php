<?php

namespace App\Http\Controllers\Users;

use App\Handlers\GoogleVisionHandler;
use App\Handlers\MediaHandler;
use App\Http\Controllers\Controller;
use App\Http\Resources\MediaResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MediaPhotoController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection|JsonResponse
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

        $count = $mediaHandler->getPhotoMediaCount($user);
        if ($count >= 99) {
            return response()->json([
                'error' => 'The number of allowed images is 99 at most.'
            ], 422);
        }

        try {
            $mediaHandler->savePhotoMediaFromUploadedFile($user, $uploadedFile);
        } catch (\Throwable $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 422);
        }

        $media = $user->refresh()->getMedia('photos');

        return MediaResource::collection($media);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function update(
        int $id,
        Request $request,
        MediaHandler $mediaHandler,
        GoogleVisionHandler $googleVisionHandler
    ) {
        $request->validate([
            'image' => ['required', 'image'],
        ]);

        $user = $request->user();
        $uploadedFile = $request->file('image');

        try {
            $mediaHandler->replacePhotoMedia($id, $user, $uploadedFile);
        } catch (\Throwable $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 422);
        }

        $media = $user->refresh()->getMedia('photos');

        return MediaResource::collection($media);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function reorder(int $id, Request $request, MediaHandler $mediaHandler)
    {
        $user = $request->user();

        try {
            $mediaHandler->reorderPhotoMedia($id, $user);
        } catch (\Throwable $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 422);
        }

        $media = $user->refresh()->getMedia('photos');

        return MediaResource::collection($media);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function destroy(int $id, Request $request, MediaHandler $mediaHandler)
    {
        $user = $request->user();

        try {
            $mediaHandler->removePhotoMediaById($id, $user);
        } catch (\Throwable $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 422);
        }

        $media = $user->refresh()->getMedia('photos');

        return MediaResource::collection($media);
    }
}
