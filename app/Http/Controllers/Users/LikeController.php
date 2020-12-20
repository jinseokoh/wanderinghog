<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function favorite($uid, $aid, Request $request)
    {
        $user = $request->user();

        $appointment = Appointment::findOrFail($aid);
        $user->favorite($appointment);

        return response()->json([
            'data' => 'ok',
        ]);
    }

    public function unfavorite($uid, $aid, Request $request)
    {
        $user = $request->user();

        $appointment = Appointment::findOrFail($aid);
        $user->unfavorite($appointment);

        return response()->json([
            'data' => 'ok',
        ]);
    }
}
