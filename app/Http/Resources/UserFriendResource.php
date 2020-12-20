<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Jenssegers\Optimus\Optimus;

// note that this is not related to USER model as opposed to UserResource
// as we've used database query builder instead of eloquent builder
class UserFriendResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'hashed_id' => app(Optimus::class)->encode($this->id),
            'username' => $this->username,
            'gender' => $this->gender,
            'age' => Carbon::parse($this->dob)->age,
            'avatar' => $this->avatar ?: 'https://amuse-images.s3.ap-northeast-2.amazonaws.com/assets/images/avatar.png',
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'phone_verified_at' => $this->phone_verified_at,
            'profession_verified_at' => $this->profession_verified_at,
            'bookmarked' => $this->relation === 'favorite'
        ];
    }
}
