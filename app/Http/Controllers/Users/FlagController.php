<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FlagController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index($id, Request $request)
    {
        $user = $request->user();
        $body = $request->input('body');
        $other = User::find($id);
        $other->flag($user->id, $body);

        return response()->json([
            'data' => 'ok'
        ]);
    }
}
