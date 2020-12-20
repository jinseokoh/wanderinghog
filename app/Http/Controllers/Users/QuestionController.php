<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * @param Request $request
     * @return QuestionResource
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $question = Question::whereNotIn('id', function ($query) use ($userId) {
                $query->select('question_id')
                    ->from('answers')
                    ->where('user_id', $userId);
            })
            ->inRandomOrder()
            ->first();

        return new QuestionResource($question);
    }
}
