<?php

namespace App\Http\Controllers\Appointments;

use App\Handlers\AppointmentHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\PartyDecisionUpdateRequest;
use App\Http\Resources\PartyResource;
use App\Models\Party;
use Illuminate\Http\JsonResponse;

class DecisionController extends Controller
{
    /**
     * @param int $aid
     * @param int $pid
     * @param PartyDecisionUpdateRequest $request
     * @param AppointmentHandler $appointmentHandler
     * @return PartyResource|JsonResponse
     */
    public function index(
        int $aid,
        int $pid,
        PartyDecisionUpdateRequest $request,
        AppointmentHandler $appointmentHandler
    ) {
        $user = $request->user();
        $userId = $user->id;
        $appointment = $appointmentHandler->findById($aid);
        $hostParty = $appointment->hostParty();
        $party = Party::findOrFail($pid);

        if ($hostParty->user_id != $userId && $hostParty->friend_id != $userId) {
            return response()->json([
                'error' => 'you are not the host of this event.'
            ], 422);
        }

        $decision = $request->getDecision();

        if ($hostParty->user_id === $user->id) {
            $party->user_decision = $decision;
            $party->save();
        } else {
            $party->friend_decision = $decision;
            $party->save();
        }

        return new PartyResource($party->fresh());
    }
}
