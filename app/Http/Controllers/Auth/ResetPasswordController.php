<?php

namespace App\Http\Controllers\Auth;

use App\Handlers\OtpHandler;
use App\Handlers\UserHandler;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * @var OtpHandler
     */
    private OtpHandler $otpHandler;
    /**
     * @var UserHandler
     */
    private UserHandler $userHandler;


    public function __construct(UserHandler $userHandler, OtpHandler $otpHandler)
    {
        $this->userHandler = $userHandler;
        $this->otpHandler = $otpHandler;
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $email = $request->get('email');
        $token = $request->get('token');
        $password = $request->get('password');

        $request->validate($this->rules(), $this->validationErrorMessages());

        $user = $this->userHandler->findByEmail($email);
        $key = $this->otpHandler->getCacheKey('reset', $user->id);

        if (\Cache::get($key) !== $token) {
            return $this->sendResetFailedResponse($request, Password::INVALID_TOKEN);
        }

        $this->resetPassword($user, $password);
        $this->deleteToken($user);

        return $this->sendResetResponse($request, Password::PASSWORD_RESET);
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only(
            'token', 'email', 'password',
        );
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  Request $request
     * @param  string  $response
     * @return JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        return response()->json(['status' => trans($response)]);
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  Request $request
     * @param  string  $response
     * @return JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json([
            'message' => trans($response),
            'errors' => [
                'password' => [ trans($response) ]
            ]
        ], 422);
    }

    private function deleteToken(CanResetPasswordContract $user)
    {
        \DB::table('password_resets')->where('email', $user->getEmailForPasswordReset())->delete();
    }
}
