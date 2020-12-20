<?php

namespace App\Http\Controllers\Users;

use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserDetailResource;
use App\Http\Controllers\Controller;
use App\Handlers\UserHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @param UserHandler $userHandler
     * @return UserResource|UserDetailResource
     */
    public function index(Request $request, UserHandler $userHandler)
    {
        $user = $request->user();

        if ($request->has('detail')) {
            $user = $userHandler->findById(
                $user->id,
                [
                    'media' => function ($query) {
                        $query->orderBy('order_column');
                    },
                    'profile',
                    'preference',
                    'socialProviders',
                ]
            );
            return new UserDetailResource($user);
        }

        return new UserResource($user);
    }

    /**
     * @param int $id
     * @param Request $request
     * @param UserHandler $userHandler
     * @return UserDetailResource|UserResource|JsonResponse
     */
    public function show(
        int $id,
        Request $request,
        UserHandler $userHandler
    )
    {
        try {
            if ($request->has('detail')) {
                $user = $userHandler->findById(
                    $id,
                    [
                        'media' => function ($query) {
                            $query->orderBy('order_column');
                        },
                        'profile',
                        'preference',
                        'socialProviders',
                    ]
                );

                return new UserDetailResource($user);
            } else {
                $user = $userHandler->findById($id);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'data' => 'No user with the id is found.',
            ]);
        }

        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = [];

        if ($username = $request->get('username')) {
            $data['username'] = $username;
        }
        if ($name = $request->get('name')) {
            $data['name'] = $name;
        }
        if ($gender = $request->get('gender')) {
            $data['gender'] = $gender;
        }
        if ($dob = $request->get('dob')) {
            $data['dob'] = $dob;
        }
        if ($locale = $request->get('locale')) {
            $data['locale'] = $locale;
        }

        if (!count($data)) {
            return response()->json([
                'error' => 'not a valid request'
            ], 422);
        }

        $user->update($data);
        return response()->json([
            'data' => 'ok',
        ]);
    }

    public function kakao(Request $request): JsonResponse
    {
        $user = $request->user();
        $flag = $user
            ->socialProviders()
            ->where('provider', 'kakao')
            ->first();

        return response()->json([
            'data' => !!$flag,
        ]);
    }
}
