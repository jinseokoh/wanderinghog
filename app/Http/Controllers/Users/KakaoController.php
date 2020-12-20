<?php

namespace App\Http\Controllers\Users;

use App\Handlers\UserHandler;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Kakao;
use Illuminate\Http\Request;

class KakaoController extends Controller
{
    /**
     * deprecated
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $socialProvider = $user
            ->socialProviders()
            ->where('provider', 'kakao')
            ->first();

        $users = collect([]);
        if ($socialProvider) {
            $kakaoId = $socialProvider->provider_id;
            $kakao = Kakao::where('kakao_id', $kakaoId)->first();
            if ($kakao) {
                $users = $kakao->users;
            }
        }

        return UserResource::collection($users);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upsert(Request $request, UserHandler $handler)
    {
        $request->validate([
            'kakaos' => 'required|array',
        ]);

        $user = $request->user();
        $kakaos = $request->get('kakaos', []);

        foreach ($kakaos as $kakao) {
            $kakao = Kakao::updateOrCreate([
                'kakao_id' => $kakao['kakao_id'],
            ], [
                'name' => $kakao['name'],
                'avatar' => $kakao['avatar'],
            ]);

            // if already a registered user, send him/her a friend request upfront.
            $friend = $handler->findBySocialProvider('kakao', $kakao['kakao_id']);
            if ($friend) {
                $user->befriend($friend);
            }

            $user->kakaos()->attach($kakao->id);
        }

        return response()->json([
            'data' => 'ok',
        ]);
    }
}
