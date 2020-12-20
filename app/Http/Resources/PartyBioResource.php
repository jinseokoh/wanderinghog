<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class PartyBioResource extends PartyResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'user' => new UserDetailResource($this->whenLoaded('user')),
            'friend' => new UserDetailResource($this->whenLoaded('friend')),
        ]);
    }
}
