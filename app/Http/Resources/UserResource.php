<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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

        return [
            'id' => $this->id,
            'hashed_id' => $this->hashedId,
            'username' => $this->username,
            'gender' => $this->gender,
            'age' => $this->age(),
            'avatar' => $this->avatar ?: 'https://amuse-images.s3.ap-northeast-2.amazonaws.com/assets/images/avatar.png',
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'phone_verified_at' => $this->phone_verified_at,
            'profession_verified_at' => $this->profession_verified_at,
        ];
    }
}
