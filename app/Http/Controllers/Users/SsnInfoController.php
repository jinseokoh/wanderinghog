<?php

namespace App\Http\Controllers\Users;

use App\Handlers\OtpHandler;
use App\Http\Requests\UserVerifyRequest;
use App\Http\Controllers\Controller;
use App\Notifications\SendVerificationSms;
use App\Support\AgeCalculator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SsnInfoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param OtpHandler $otpHandler
     */
    public function __construct()
    {
//        $this->middleware('throttle:3,2')->only('store'); // allow 3 times within 2 minutes
//        $this->middleware('throttle:6,2')->only('update'); // allow 6 times within 2 minutes
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request, AgeCalculator $ageCalculator): JsonResponse
    {
        $user = $request->user();
        if ($user->dob->year === 1000) {
            $range = $ageCalculator->getYearRange(
                $user->preference->min_age,
                $user->preference->max_age
            );
            $month = $user->dob->month;
            $day = $user->dob->day;
        } else {
            $range = $ageCalculator->getYearRange(20, 80);
            $month = null;
            $day = null;
        }

        return response()->json([
            'data' => [
                'years' => $range,
                'month' => $month,
                'day' => $day
            ]
        ]);
    }

    /**
     * @param UserVerifyRequest $request
     * @return JsonResponse
     */
    public function store(
        UserVerifyRequest $request
    ): JsonResponse {
        $user = $request->user();

        $name = $request->getName();
        $gender = $request->getGender();
        $dob = $request->getDob();
        $phone = $request->getPhone();

        $user = tap($user)->update([
            'name' => $name,
            'gender' => $gender,
            'dob' => $dob,
            'phone' => $phone,
        ]);

        $user->notify(new SendVerificationSms());

        return response()->json([
           'data' => 'ok',
        ]);
    }

    /**
     * @param Request $request
     * @param OtpHandler $otpHandler
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, OtpHandler $otpHandler): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|string',
        ]);

        $user = $request->user();
        $key = $otpHandler
            ->getCacheKey('phone', $user->id);
        if ($request->get('otp') !== \Cache::get($key)) {
            $user->update([
                'phone' => null,
                'phone_verified_at' => null,
            ]);
            throw new AuthorizationException;
        }

        $user->update([
            'phone' => $request->get('phone'),
            'phone_verified_at' => Carbon::now()->toDateString(),
        ]);
        return response()->json([
            'data' => 'ok',
        ]);
    }
}
