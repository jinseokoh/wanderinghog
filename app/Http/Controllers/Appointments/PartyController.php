<?php

namespace App\Http\Controllers\Appointments;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartyRegisterRequest;
use App\Http\Resources\PartyBioResource;
use App\Http\Resources\PartyResource;
use App\Http\Resources\PartyDetailResource;
use App\Models\Appointment;
use App\Models\Party;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PartyController extends Controller
{
    /**
     * @param int $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(int $id)
    {
        $paginator = Party::with([
            'user',
            'friend'
        ])
            ->where('appointment_id', $id)
            ->where('is_host', false)
            ->paginate(10);

        return PartyDetailResource::collection($paginator);
    }

    /**
     * @param int $id
     * @param int $pid
     * @return PartyDetailResource
     */
    public function show(int $id, int $pid)
    {
        $party = Party::with([
            'user',
            'friend'
        ])
            ->where('id', $pid)
            ->firstOrFail();

        return new PartyDetailResource($party);
    }

    /**
     * @param int $id
     * @param int $pid
     * @return PartyBioResource
     */
    public function bio(int $id, int $pid)
    {
        $party = Party::with([
            'user.profile',
            'friend.profile'
        ])
            ->where('id', $pid)
            ->firstOrFail();

        return new PartyBioResource($party);
    }

    /**
     * @param int $id
     * @param PartyRegisterRequest $request
     * @return JsonResponse
     */
    public function store(int $id, PartyRegisterRequest $request): JsonResponse
    {
        /** @var Appointment $appointment */
        $appointment = Appointment::with('parties')->findOrFail($id);
        $questions = $appointment->questions;

        $userId = $request->user()->id;
        $friendId = $request->getFriendId();
        $relationType = $request->getRelationType();

        // validation #1
        $answers = $request->getAnswers();
        if (count($questions) !== count($answers)) {
            return response()->json([
                'error' => '# of given answers is not match.'
            ], 422);
        }

        // validation #2
        $hostParty = $appointment
            ->parties
            ->first(function ($item) {
                return $item->is_host;
            });
        $parties = [$hostParty->user_id, $hostParty->friend_id, $userId, $friendId];
        if (count(array_unique($parties)) < 4) {
            return response()->json([
                'error' => 'a host can not be a guest.'
            ], 422);
        }

        \DB::beginTransaction();
        try {
            $party = Party::create([
                'appointment_id' => $appointment->id,
                'user_id' => $userId,
                'friend_id' => $friendId,
                'relation_type' => $relationType,
                'answers' => $answers,
                'is_host' => false,
            ]);
            \DB::commit();
        } catch (\Throwable $exception) {
            \DB::rollBack();
            \Log::error($exception->getMessage());

            return response()->json([
                'error' => 'DB constraint error'
            ], 422);
        }

        return response()->json([
            'data' => 'ok'
        ]);
    }

    /**
     * @param int $id
     * @param int $pid
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(int $id, int $pid, Request $request): JsonResponse
    {
        $user = $request->user();
        $party = Party::find($pid);
        if ($user->id === $party->user_id || $user->id === $party->friend_id) {
            $party->delete();
            // todo. send push notification to friend
            // it has been deleted by your buddy blah blah
        } else {
            return response()->json([
                'error' => 'access denied'
            ], 422);
        }

        return response()->json([
            'data' => 'ok'
        ]);
    }
}
