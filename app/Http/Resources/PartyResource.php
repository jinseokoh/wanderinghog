<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        // return parent::toArray($request);

        return
        [
            'id' => $this->id,
            'group' => $this->is_host ? 'host' : 'guest',
            'relation_type' => $this->relation_type,
            'user_decision' => $this->user_decision,
            'friend_decision' => $this->friend_decision,
            // 'genders' => [ $this->user->gender, $this->friend->gender ],
            'user' => new UserResource($this->whenLoaded('user')),
            'friend' => new UserResource($this->whenLoaded('friend')),
        ];
    }
}
