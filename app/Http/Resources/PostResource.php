<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
//use App\Http\Resources\Category as CategoryResource;
//use App\Http\Resources\Partie as PartieResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'user' => new UserResource($this->user),
            'user_tier' => optional($this->user->categories->where('id', $this->category->id)->first())->pivot->tier,
//            'category' => new CategoryResource($this->category),
//            'parties' => PartieResource::collection($this->parties),
            'title' => $this->title,
            'description' => $this->description,
            'venue' => $this->venue,
            'location' => $this->location,

            'ages' => $this->ages,
            'skills' => $this->skills,
            'bill_option' => $this->bill_option,
            'price_option' => $this->price_option,
            'party_options' => $this->partie_options,

            'is_private' => $this->is_private,
            'is_featured' => $this->is_featured,
            'scheduled_at' => $this->scheduled_at,
        ];
    }
}
