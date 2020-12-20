<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  Request $request
     * @param  string  $response
     * @return JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return new JsonResponse(['message' => trans($response)], 200);
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  Request $request
     * @param  string  $response
     * @return JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response()->json([
            'message' => 'failed sending reset link',
            'errors' => [
                'email' => [ trans($response) ]
            ]
        ], 422);
    }
}
