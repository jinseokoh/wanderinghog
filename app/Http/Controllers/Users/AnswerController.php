<?php

namespace App\Http\Controllers\Users;

use App\Handlers\AnswerHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnswerStoreRequest;
use App\Http\Resources\AnswerResource;
use App\Models\Answer;
use App\Models\User;
use App\Support\ImageMetaReader;
use Illuminate\Http\JsonResponse;

class AnswerController extends controller
{
    /**
     * @param int $id
     * @param AnswerHandler $answerHandler
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(int $id, AnswerHandler $answerHandler)
    {
        $user = User::findOrFail($id);

        $answers = $answerHandler
            ->fetchByUser($user);

        return AnswerResource::collection($answers);
    }

    /**
     * https://github.com/spatie/laravel-medialibrary/issues/1613
     *
     * @param AnswerStoreRequest $request
     * @param ImageMetaReader $imageMetaReader
     * @return JsonResponse|AnswerResource
     */
    public function store(
        AnswerStoreRequest $request,
        ImageMetaReader $imageMetaReader
    ) {
        $userId = $request->user()->id;
        $questionId = $request->getQuestionId();
        $userAnswer = $request->getAnswer();

        try {
            /** @var Answer $answer */
            $answer = Answer::updateOrCreate(
                [
                    'question_id' => $questionId,
                    'user_id' => $userId,
                ],
                [
                    'body' => $userAnswer,
                ]
            );
        } catch (\Throwable $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 422);
        }

        $uploadedFile = $request->file('image');
        if ($uploadedFile) {
            \DB::beginTransaction();
            try {
                $mimetype = $uploadedFile->getClientMimeType();
                $randomizedName = $imageMetaReader->randomizedName($mimetype);
                // $exif = exif_read_data($uploadedFile);
                // $takenAt = $imageMetaReader->dateTaken($exif);
                $answer->addMedia($uploadedFile)
                    ->preservingOriginal()
                    ->usingFileName($randomizedName)
                    ->toMediaCollection('answers');
                // $answer->update([
                //     'taken_at' => $takenAt,
                // ]);
                \DB::commit();
            } catch (\Throwable $exception) {
                \DB::rollBack();
                \Log::error($exception->getMessage());

                return response()->json([
                    'error' => $exception->getMessage(),
                ], 422);
            }
        }

        return new AnswerResource($answer);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        /** @var Answer $answer */
        $answer = Answer::findOrFail($id);

        try {
            $answer->delete();
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
