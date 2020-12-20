<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $paginator = $user->rooms()
            ->with('users')
            ->where('is_active', true)
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return RoomResource::collection($paginator);
    }

    /**
     * @param int $id
     * @return RoomResource
     */
    public function show(int $id)
    {
        $room = Room::with([
            'users',
        ])
            ->where('id', $id)
            ->firstOrFail();

        return new RoomResource($room);
    }

    /**
     * Todo. see if it's going to be used.
     *       I don't see it's necessary
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $uuid = Str::uuid();
        $title = $request->get('title');
        $userIds = $request->get('user_ids', []);
        if (count($userIds) < 4) {
            return response()->json([
                'error' => '# of participants has to be 4'
            ], 422);
        }

        \DB::beginTransaction();
        try {
            $room = Room::create([
                'uuid' => $uuid,
                'title' => $title,
            ]);
            $room->users()->sync($userIds);
            \DB::commit();
        } catch (\Throwable $exception) {
            \DB::rollBack();

            return response()->json([
                'error' => $exception->getMessage(),
            ], 422);
        }

        return response()->json(['message' => 'ok']);
    }
}
