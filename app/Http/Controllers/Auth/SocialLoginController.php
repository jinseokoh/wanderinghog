<?php

namespace App\Http\Controllers\Auth;

use App\Events\KakaoRegistered;
use App\Handlers\UserHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\SocialLoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTGuard;

/**
 * Class SocialLoginController
 * @package App\Http\Controllers\Auth
 */
class SocialLoginController extends Controller
{
    use AuthenticatesUsers;

    private $userHandler;

    /**
     * Create a new controller instance.
     *
     * @param UserHandler $userHandler
     */
    public function __construct(UserHandler $userHandler)
    {
        $this->userHandler = $userHandler;
    }

    /**
     * Obtain the user information from the provider.
     *
     * @param string $provider
     * @param SocialLoginRequest $request
     * @return mixed
     */
    public function index(String $provider, SocialLoginRequest $request)
    {
        $meta = [];
        $socialUserDto = $request->getSocialUserDto();

        try {
            $user = $this->userHandler
                ->findBySocialProvider($provider, $socialUserDto->getId());
            $user = $this->userHandler
                ->updateUuid($user, $request->get('uuid'));
        } catch (\Exception $exception) {
            try { // 기존 회원
                $user = $this->userHandler
                    ->findByEmail($socialUserDto->getEmail());
                $user = $this->userHandler
                    ->updateUuid($user, $request->get('uuid'));
            } catch (\Exception $exception) { // 새로운 회원
                $user = $this->userHandler
                    ->createUserFromSocialUserDto($socialUserDto);
            }
            $user->socialProviders()->create([
                'provider' => $provider,
                'provider_id' => $socialUserDto->getId(),
            ]);
        }

        if ($provider === 'kakao') {
            event(new KakaoRegistered($user, $socialUserDto->getId()));
        }

        $token = $this->guard()->login($user);
        $expiration = $this->guard()->getPayload()->get('exp');

        return (new UserResource($user))->additional([
            'meta' => array_merge($meta, [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $expiration - time(),
            ])
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return JWTGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
