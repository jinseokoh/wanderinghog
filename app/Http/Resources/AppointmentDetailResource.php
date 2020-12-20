<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class AppointmentDetailResource extends AppointmentResource
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
            'description' => $this->description,
            'estimate' => $this->estimate,
            'is_closed' => $this->is_closed,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'venue' => new VenueResource($this->whenLoaded('venue')),
            'questions' => $this->questions,
        ]);
    }
}
