<?php

namespace App\Http\Controllers\Admin\Api\Comments;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function toggle(int $id, Request $request)
    {
        $comment = Comment::find($id);
        $comment->is_approved = ! $comment->is_approved;
        $comment->save();

        return response()->json([
            'status' => $comment->is_approved,
        ]);
    }
}
