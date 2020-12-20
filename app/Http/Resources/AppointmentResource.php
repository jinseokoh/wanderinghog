<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
        $parties = $this->whenLoaded('parties');
        $favoriters = $this->whenLoaded('favoriters');
        $userId = optional($request->user())->id;

        $isHost = false;
        $isGuest = false;
        $isFavorite = false;

        if ($userId) {
            $isHost = !! $parties
                ->first(function ($item) use ($userId) {
                    return
                        $item->is_host === true &&
                        ($item->user_id === $userId || $item->friend_id === $userId);
                });
            $isGuest = !! $parties
                ->first(function ($item) use ($userId) {
                    return
                        $item->is_host === false &&
                        ($item->user_id === $userId || $item->friend_id === $userId);
                });
            $isFavorite = !! $favoriters
                ->first(function ($item) use ($userId) {
                    return $item->id === $userId;
                });
        }

        return [
            'id' => $this->id,
            'theme_type' => $this->theme_type,
            'title' => $this->title,
            'view_count' => $this->view_count,
            'like_count' => $this->like_count,
            'expired_at' => $this->expired_at,
            'regions' => RegionResource::collection($this->whenLoaded('regions')),
            'parties' => PartyResource::collection($this->parties),
            'media' => $this->image() ? $this->image() : [
                "id" => 1,
                "image" => "https://avatars0.githubusercontent.com/u/9064066?v=4&s=460",
                "thumb" => "https://avatars0.githubusercontent.com/u/9064066?v=4&s=460"
            ],
            'is_host' => $isHost,
            'is_guest' => $isGuest,
            'is_favorite' => $isFavorite,
            'is_closed' => $this->is_closed,
        ];
    }
}
