<?php

namespace App\Http\Controllers\Users;

use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Handlers\UserHandler;
use App\Support\PhoneNumberParser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * @var UserHandler
     */
    private UserHandler $userHandler;

    public function __construct(UserHandler $userHandler)
    {
        $this->userHandler = $userHandler;
    }

    /**
     * @param Request $request
     * @return UserResource|JsonResponse
     */
    public function index(Request $request)
    {
        if ($username = $request->input('username')) {
            try {
                $user =  $this->userHandler->findByName($username);
            } catch (\Throwable $e) {
                return response()->json([
                    'error' => 'not found.',
                ], 404);
            }
        } else if ($email = $request->input('email')) {
            try {
                $user = $this->userHandler->findByEmail($email);
            } catch (\Throwable $e) {
                return response()->json([
                    'error' => 'not found.',
                ], 404);
            }
        } else if ($request->input('phone')) {
            $phone = (new PhoneNumberParser())->parse($request->input('phone'));
            try {
                $user = $this->userHandler->findByPhone($phone);
            } catch (\Throwable $e) {
                return response()->json([
                    'error' => 'not found.',
                ], 404);
            }
        } else {
            return response()->json([
                'error' => 'what do you want?',
            ], 400);
        }

        return new UserResource($user);
    }
}
