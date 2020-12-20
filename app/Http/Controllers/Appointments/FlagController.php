<?php

namespace App\Http\Controllers\Appointments;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FlagController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index($id, Request $request)
    {
        $user = $request->user();
        $body = $request->input('body');
        $appointment = Appointment::find($id);
        $appointment->flag($user->id, $body);

        return response()->json([
            'data' => 'ok'
        ]);
    }
}
