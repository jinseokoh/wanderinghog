<?php

namespace App\Http\Controllers\Categories;

use App\Handlers\QuestionHandler;
use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * @param QuestionHandler $handler
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, QuestionHandler $handler)
    {
        $slug = $request->input('slug');
        if ($slug) {
            $collection = $handler->findNodeBySlug($slug)->descendants;
        } else {
            $collection = Question::where('depth', '>', 0)->defaultOrder()->get();
        }

        return QuestionResource::collection($collection);
    }

    /**
     * @param QuestionHandler $handler
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function slugs(Request $request)
    {
        $collection = Question::where('depth', '=', 1)->get();

        return QuestionResource::collection($collection);
    }
}
