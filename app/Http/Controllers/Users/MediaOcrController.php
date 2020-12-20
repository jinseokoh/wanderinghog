<?php

namespace App\Http\Controllers\Users;

use App\Handlers\GoogleVisionHandler;
use App\Handlers\MediaHandler;
use App\Http\Controllers\Controller;
use App\Support\PhoneNumberParser;
use App\Support\SchoolNameParser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaOcrController extends Controller
{
    /**
     * 학생증
     *
     * @param Request $request
     * @param MediaHandler $mediaHandler
     * @param GoogleVisionHandler $googleVisionHandler
     * @param SchoolNameParser $schoolNameParser
     * @return JsonResponse
     */
    public function storeSchool(
        Request $request,
        MediaHandler $mediaHandler,
        GoogleVisionHandler $googleVisionHandler,
        SchoolNameParser $schoolNameParser
    ): JsonResponse {
        $request->validate([
            'image' => ['required', 'image'],
        ]);

        $user = $request->user();
        $uploadedFile = request()->file('image');
        $contents = file_get_contents($uploadedFile->getRealPath());

        try {
            $response = $googleVisionHandler->detectText($contents);
        } catch (\Throwable $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 422);
        }
        $googleVisionResultString = trim($response->getDescription());
        $school = $schoolNameParser->college($googleVisionResultString);

        try {
            $mediaHandler->saveUserMediaFromUploadedFile($user, $uploadedFile, ['school' => !!$school]);
            if ($school) {
                $user->profile->update([
                    'profession' => $school ? $school : 'n/a',
                    // 'profession_verified_at' => $user->profile->freshTimestamp(),
                ]);
            }
        } catch (\Throwable $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 422);
        }

        return response()->json([
            'data' => [
                'profession' => $school,
            ],
        ]);
    }

    /**
     * 명함
     *
     * @param Request $request
     * @param MediaHandler $mediaHandler
     * @param GoogleVisionHandler $googleVisionHandler
     * @param PhoneNumberParser $phoneNumberParser
     * @return JsonResponse
     * @throws \Google\ApiCore\ValidationException
     */
    public function storePhone(
        Request $request,
        MediaHandler $mediaHandler,
        GoogleVisionHandler $googleVisionHandler,
        PhoneNumberParser $phoneNumberParser
    ): JsonResponse {
        $request->validate([
            'image' => ['required', 'image'],
        ]);

        $user = $request->user();
        $uploadedFile = request()->file('image');
        $contents = file_get_contents($uploadedFile->getRealPath());

        try {
            $response = $googleVisionHandler->detectText($contents);
        } catch (\Throwable $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 422);
        }
        $googleVisionResultString = trim($response->getDescription());
        $phone = $phoneNumberParser->parse($googleVisionResultString);

        try {
            $mediaHandler->saveUserMediaFromUploadedFile($user, $uploadedFile, ['phone' => !!$phone]);
            if ($phone) {
                $user->profile->update([
                    'profession' => $phone ? $phone : 'n/a',
                    // 'profession_verified_at' => $user->profile->freshTimestamp(),
                ]);
            }
        } catch (\Throwable $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 422);
        }

        return response()->json([
            'data' => [
                'profession' => $phone,
            ],
        ]);
    }
}
