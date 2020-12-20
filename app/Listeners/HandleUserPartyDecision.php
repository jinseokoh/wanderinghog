<?php

namespace App\Listeners;

use App\Events\PartyDecisionMadeByUser;
use App\Models\Party;
use App\Models\Room;
use App\Models\User;
use App\Repositories\DynamoDbRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;

class HandleUserPartyDecision implements ShouldQueue
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
     * @param  PartyDecisionMadeByUser  $event
     * @return void
     */
    public function handle(PartyDecisionMadeByUser $event)
    {
        \Log::info('user decision has been made.');
        $party = $event->party;
        $friend = User::findOrFail($party->friend_id);

        if ($party->friend_decision) {
            \Log::info('friend decision is there.');
            if ($party->user_decision === 'approved' &&
                $party->friend_decision === 'approved'
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
                // todo. notify friend chat room has just opened
            } else if ($party->friend_decision == 'approved') {
                // todo. notify friend this user denied the party (could be ignored)
                \Log::info('todo. notify friend this user denied the party');
            }
        } else {
            \Log::info('friend decision is NOT there.');
            if ($party->user_decision === 'approved') {
                // todo. notify friend this user approved the party
                \Log::info('todo. notify friend this user approved the party');
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
