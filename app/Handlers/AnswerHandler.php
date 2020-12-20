<?php

namespace App\Handlers;

use App\Models\Answer;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AnswerHandler
{
    /**
     * find a model
     *
     * @param int $id
     * @return Model
     */
    public function findById(int $id)
    {
        return Answer::with([
            'media',
            'question',
        ])
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * fetch all answers from this user
     *
     * @param User $user
     * @return LengthAwarePaginator
     */
    public function fetchByUser(User $user): LengthAwarePaginator
    {
        return $user
            ->answers()
            ->with([
                'media',
                'question',
            ])
            ->orderBy('id', 'DESC')
            ->paginate(10);
    }

    /**
     * fetch all experiences
     *
     * @return LengthAwarePaginator
     */
    public function fetch(): LengthAwarePaginator
    {
        return Answer::with([
            'media',
            'question',
        ])
            ->orderBy('id', 'DESC')
            ->paginate(10);
    }

    /**
     * @param User $user
     * @param string $answer
     * @return Answer
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function create(User $user, int $questionId, string $answer): Answer
    {
        return Answer::create([
            'question_id' => $questionId,
            'user_id' => $user->id,
            'answer' => $answer,
        ]);

        // $user->notify(new AnswerPublished($experience));
    }
}
