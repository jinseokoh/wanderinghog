<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\AppInvitation;
use App\Notifications\SendAppInvitationSms;
use App\Support\PhoneNumberParser;
use Illuminate\Http\Request;
use Notification;

class AppInvitationController extends Controller
{
    public function index(Request $request, PhoneNumberParser $phoneNumberParser)
    {
        $user = $request->user();
        $phones = $request->get('phones', []);
        $goneThrough = [];
        foreach ($phones as $phone) {
            $phoneNumber = $phoneNumberParser->parse($phone);
            if ($phoneNumber) { // 정상적으로 파싱한 경우
                try {
                    AppInvitation::create([
                        'user_id' => $user->id,
                        'phone' => $phoneNumber,
                    ]);
                } catch (\Throwable $exception) {
                    return response()->json([
                        'error' => 'The number already exists in DB.'
                    ], 422);
                }

                Notification::route('AligoText', $phoneNumber)->notify(new SendAppInvitationSms($user));
                // $internationalPhoneNumber = $phoneNumberParser->international($phoneNumber);
                // Notification::route('sms', $internationalPhoneNumber)->notify(new SendAppInvitationSms($user));

                $goneThrough[] = $phoneNumber;
            }
        }

        if (count($goneThrough) > 0) {
            return response()->json([
                'data' => $goneThrough,
            ]);
        }

        return response()->json([
            'error' => 'No text message has been successfully delivered.'
        ], 422);
    }
}
