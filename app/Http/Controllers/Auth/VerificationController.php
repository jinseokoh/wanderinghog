<?php

namespace App\Http\Controllers\Auth;

use App\Handlers\OtpHandler;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Jenssegers\Optimus\Optimus;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * @var OtpHandler
     */
    private $otpHandler;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @param OtpHandler $otpHandler
     */
    public function __construct(OtpHandler $otpHandler)
    {
        $this->middleware('auth:api');
        // $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
        $this->otpHandler = $otpHandler;
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Optimus $optimus
     * @return \Illuminate\Http\Response
     *
     * @throws AuthorizationException
     */
    public function verify(Request $request, Optimus $optimus)
    {
        $userId = $optimus->decode($request->route('id'));
        if ($request->user()->getKey() !== $userId) {
            throw new AuthorizationException;
        }

        $key = $this->otpHandler->getCacheKey('email', $userId);
        if ($request->route('hash') !== \Cache::get($key)) {
            throw new AuthorizationException;
        }

        if ($request->user()->hasVerifiedEmail()) {
            return new Response('', 204);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        if ($response = $this->verified($request)) {
            return $response;
        }

        return new Response('', 204);
    }
}
