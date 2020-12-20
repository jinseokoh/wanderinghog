<?php

namespace App\Http\Controllers\Admin\Comments;

use App\Handlers\ProductHandler;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $comments = Comment::with('user')->paginate(10);

        return view('comments.index', compact('comments'));
    }
}
