<?php

namespace App\Http\Controllers\Appointments;

use App\Handlers\LikableHandler;
use App\Http\Controllers\Controller;
use App\Handlers\ProductHandler;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
    /**
     * @param int $id
     * @param Request $request
     * @param LikableHandler $likeHandler
     * @param ProductHandler $productHandler
     * @return JsonResponse
     */
    public function likes(
        int $id,
        Request $request,
        LikableHandler $likeHandler,
        ProductHandler $productHandler
    ): JsonResponse {

//        $like = new Like;
//        $like->user()->associate($request->user());
//        $product->likes()->save($like);

        $userId = $request->user()->id;
        $likableId = $id;
        $likableType = $request->input('likable_type', 'App\Models\Product');
        $score = $request->input('score', 1);

        $diff = $likeHandler->toggle($userId, $likableId, $likableType, $score);

        $product = $productHandler->updateLikeCount($id, $diff);

        return response()->json([
            'like_count' => $product->like_count,
            'interested' => $diff > 0 ? true : false,
        ], 200);
    }
}
