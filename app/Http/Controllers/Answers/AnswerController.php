<?php

namespace App\Http\Controllers\Answers;

use App\Handlers\AnswerHandler;
use App\Http\Controllers\Controller;
use App\Http\Resources\AnswerResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AnswerController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(AnswerHandler $answerHandler): AnonymousResourceCollection
    {
        $answers = $answerHandler
            ->fetch();

        return AnswerResource::collection($answers);
    }

    /**
     * @param int $id
     * @return AnswerResource
     */
    public function show(int $id, AnswerHandler $answerHandler): AnswerResource
    {
        $answer = $answerHandler
            ->findById($id);

        return new AnswerResource($answer);
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function latest(AnswerHandler $answerHandler): AnonymousResourceCollection
    {
        $answers = $answerHandler
            ->fetch();

        return AnswerResource::collection($answers);
    }
}
