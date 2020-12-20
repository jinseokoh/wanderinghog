<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class UserDetailResource extends UserResource
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
            'phone' => $this->phone,
            'dob' => $this->dob,
            'name' => $this->name,
            'profile' => new ProfileResource($this->whenLoaded('profile')),
            'preference' => new PreferenceResource($this->whenLoaded('preference')),
            'avatars' => MediaResource::collection($this->getMedia('avatars')),
            'photos' => MediaResource::collection($this->getMedia('photos')),
            'cards' => MediaResource::collection($this->getMedia('cards')),

            // 'media' => MediaResource::collection($this->whenLoaded('media')),
        ]);
    }
}
