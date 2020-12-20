<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class PartyDetailResource extends PartyResource
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
            'questions' => $this->appointment->questions,
            'answers' => $this->answers,
        ]);
    }
}
