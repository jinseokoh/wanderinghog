<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Handlers\UserHandler;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWTGuard;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * @var UserHandler
     */
    private $userHandler;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     * @param UserHandler $userHandler
     * @return void
     */
    public function __construct(UserHandler $userHandler)
    {
        $this->middleware('guest:api')->except(['logout', 'refresh']);
        $this->userHandler = $userHandler;
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return JWTGuard
     */
    protected function guard()
    {
        return Auth::guard('api');
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function credentials(Request $request): array
    {
        $email = $request->get('email');

        if (strpos($email, '@') === false) {
            return [
                'phone' => preg_replace('/[^0-9]/', '', $request->get('email')),
                'password'=>$request->get('password')
            ];
        }

        return $request->only($this->username(), 'password');
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request): bool
    {
        $token = $this->guard()
            ->claims(['uuid' => $request->get('uuid')])
            ->attempt($this->credentials($request));

        if ($token) {
            /** @var User $user */
            $user = $this->guard()->setToken($token)->user();
            $this->userHandler
                ->updateUuid($user, $request->get('uuid'));

            return true;
        }

        return false;
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  Request  $request
     * @return mixed
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        $user = $request->user();
        $token = (string) $this->guard()->getToken();
        $expiration = $this->guard()->getPayload()->get('exp');

        return (new UserResource($user))->additional([
            'meta' => [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $expiration - time(),
            ]
        ]);
    }

    /**
     * Refresh a token.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function refresh(Request $request)
    {
        $token = JWTAuth::getToken();
        try {
            $refreshedToken = JWTAuth::refresh($token);
        } catch (\Exception $e) {
            throw new AccessDeniedHttpException('The token is invalid');
        }

        return response()->json([
            'data' => [
                'token' => $refreshedToken,
                'token_type' => 'bearer',
                'expires_in' => config('jwt.ttl'),
            ]
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $this->guard()->logout();
        } catch (\Exception $e) {
            return response()->json(['data' => 'token expired.']);
        }

        return response()->json([
            'data' => 'user logged out.',
        ]);
    }
}
