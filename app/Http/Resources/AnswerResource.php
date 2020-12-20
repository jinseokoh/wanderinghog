<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
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
            'question' => new QuestionResource($this->whenLoaded('question')),
            'user' => new UserResource($this->whenLoaded('user')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'answer' => $this->body,
            'image' => $this->image(),
            'count_likes' => $this->count_likes,
            'count_flags' => $this->count_flags,
        ];
    }
}
