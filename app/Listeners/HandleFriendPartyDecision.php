<?php

namespace App\Listeners;

use App\Events\PartyDecisionMadeByFriend;
use App\Models\Party;
use App\Models\Room;
use App\Models\User;
use App\Repositories\DynamoDbRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;

class HandleFriendPartyDecision implements ShouldQueue
{
    /**
     * @var DynamoDbRepository
     */
    private DynamoDbRepository $repository;

    /**
     * @return void
     */
    public function __construct(DynamoDbRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param  PartyDecisionMadeByFriend  $event
     * @return void
     */
    public function handle(PartyDecisionMadeByFriend $event)
    {
        \Log::info('friend decision has been made.');
        $party = $event->party;
        $user = User::findOrFail($party->user_id);

        if ($party->user_decision) {
            \Log::info('user decision is there.');
            if ($party->friend_decision === 'approved' &&
                $party->user_decision === 'approved'
            ) {
                $uuid = Str::uuid();
                $title = $party->appointment->title;
                $userIds = $this->getUserIds($party);
                $room = Room::create([
                    'uuid' => $uuid,
                    'title' => $title,
                ]);
                $room->users()->sync($userIds);
                $userNames = User::whereIn('id', $userIds)
                    ->pluck('username')->toArray();
                $this->repository->put("Rooms", $uuid, $userNames);
                $party->appointment->update([
                    'is_closed' => true,
                ]);
                // todo. notify user chat room has just opened
            } else if ($party->user_decision == 'approved') {
                // todo. notify user this friend denied the party (could be ignored)
                \Log::info('todo. notify user this friend denied the party');
            }
        } else {
            \Log::info('user decision is NOT there.');
            if ($party->friend_decision === 'approved') {
                // todo. notify user this friend approved the party
                \Log::info('todo. notify user this friend approved the party');
            }
        }
    }

    // find out 4 people to mingle
    private function getUserIds($party)
    {
        $host = Party::where('is_host', true)
            ->where('appointment_id', $party->appointment_id)
            ->first();

        return [
            $host->user_id,
            $host->friend_id,
            $party->user_id,
            $party->friend_id,
        ];
    }
}
