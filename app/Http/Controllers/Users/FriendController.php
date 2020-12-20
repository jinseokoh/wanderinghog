<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserFriendResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Multicaret\Acquaintances\Models\Friendship;
use Multicaret\Acquaintances\Status;

class FriendController extends Controller
{
    public function index(Request $request)
    {
//        $query = <<<EOF
//SELECT
//    *
//FROM
//    users
//    JOIN friendships
//        ON
//        (
//            (
//                friendships.sender_id = ? AND
//                friendships.status='accepted' AND
//                users.id=friendships.recipient_id
//            )
//            OR
//            (
//                friendships.recipient_id = ? AND
//                friendships.status='accepted' AND
//                users.id=friendships.sender_id
//            )
//        )
//    LEFT JOIN interactions
//        ON
//        interactions.subject_type='App\\\\Models\\\\User' AND
//        interactions.subject_id=users.id AND
//        interactions.relation='favorite'
//    ORDER BY
//        relation
//EOF;

//        $paginator = User::where('id', '>', 0)->paginate(8);
//        dd($paginator);

        $userId = $request->user()->id;
        $paginator = \DB::table('users')
            ->join('friendships', function ($join) use ($userId) {
                $join->on(function ($query) use ($userId) {
                    $query->on('friendships.sender_id', '=', \DB::raw($userId));
                    $query->on('friendships.status', '=', \DB::raw("'accepted'"));
                    $query->on('friendships.recipient_id', '=', 'users.id');
                });
                $join->orOn(function ($query) use ($userId) {
                    $query->on('friendships.recipient_id', '=', \DB::raw($userId));
                    $query->on('friendships.status', '=', \DB::raw("'accepted'"));
                    $query->on('friendships.sender_id', '=', 'users.id');
                });
            })
            ->leftJoin('interactions', function ($join) use ($userId) {
                $join->on('interactions.user_id', '=', \DB::raw($userId));
                $join->on('interactions.subject_id', '=', 'users.id');
                $join->on('interactions.subject_type', '=', \DB::raw("'App\\\\Models\\\\User'"));
                $join->on('interactions.relation', '=', \DB::raw("'favorite'"));
            })
            ->select(['users.*', 'interactions.relation'])
            ->orderBy('relation', 'DESC')
            ->orderBy('id', 'DESC')
            ->paginate($perPge = 8);

        return UserFriendResource::collection($paginator);
    }

    public function bookmark(Request $request)
    {
        $userId = $request->user()->id;
        $paginator = \DB::table('users')
            ->join('friendships', function ($join) use ($userId) {
                $join->on(function ($query) use ($userId) {
                    $query->on('friendships.sender_id', '=', \DB::raw($userId));
                    $query->on('friendships.status', '=', \DB::raw("'accepted'"));
                    $query->on('friendships.recipient_id', '=', 'users.id');
                });
                $join->orOn(function ($query) use ($userId) {
                    $query->on('friendships.recipient_id', '=', \DB::raw($userId));
                    $query->on('friendships.status', '=', \DB::raw("'accepted'"));
                    $query->on('friendships.sender_id', '=', 'users.id');
                });
            })
            ->join('interactions', function ($join) use ($userId) {
                $join->on('interactions.user_id', '=', \DB::raw($userId));
                $join->on('interactions.subject_id', '=', 'users.id');
                $join->on('interactions.subject_type', '=', \DB::raw("'App\\\\Models\\\\User'"));
                $join->on('interactions.relation', '=', \DB::raw("'favorite'"));
            })
            ->select(['users.*', 'interactions.relation'])
            ->orderBy('relation', 'DESC')
            ->paginate($perPge = 8);

        return UserFriendResource::collection($paginator);
    }

    public function pending(Request $request)
    {
        $user = $request->user();

        $ids = Friendship::where('recipient_id', $user->id)
            ->where('status', Status::PENDING)
            ->pluck('sender_id');
        $senders = User::whereIn('id', $ids)->paginate();

        return UserResource::collection($senders);
    }

    public function block(Request $request)
    {
        $user = $request->user();

        $ids = Friendship::where('sender_id', $user->id)
            ->where('status', Status::BLOCKED)
            ->pluck('recipient_id');
        $senders = User::whereIn('id', $ids)->paginate();

        return UserResource::collection($senders);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $friendId = $request->get('friend_id');
        $friendIds = $this->getIdsOfFriends($user->id);

        if (! in_array($friendId, $friendIds)) {
            return response()->json([
                'error' => 'must be friends before bookmark him/her.',
            ], 422);
        }

        $friend = User::findOrFail($friendId);
        try {
            $user->favorite($friend);
        } catch (\Throwable $exception) {
            return response()->json([
                'error' => 'ignored',
            ], 422);
        }

        return response()->json([
            'data' => 'ok',
        ], 201);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        $friendId = $request->get('friend_id');
        $friend = User::findOrFail($friendId);

        try {
            $user->unfavorite($friend);
        } catch (\Throwable $exception) {
            return response()->json([
                'error' => 'ignored',
            ], 422);
        }

        return response()->json([
            'data' => 'ok',
        ], 200);
    }

    // ================================================================================
    // helpers
    // ================================================================================

    public function getIdsOfFriends(int $userId): array
    {
        $friendships = Friendship::where('status', Status::ACCEPTED)
            ->where(function ($query) use ($userId) {
                $query
                    ->where(function ($q) use ($userId) {
                        $q->where('sender_id', $userId);
                    })
                    ->orWhere(function ($q) use ($userId) {
                        $q->where('recipient_id', $userId);
                    });
            })
            ->get(['sender_id', 'recipient_id']);
        $recipients = $friendships->pluck('recipient_id')->all();
        $senders = $friendships->pluck('sender_id')->all();

        return array_diff(
            array_unique(array_merge($recipients, $senders)),
            [ $userId ]
        );
    }
}
