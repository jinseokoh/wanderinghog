<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class MyActivityResource extends ActivityResource
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
            'tier' => $this->pivot->tier,
        ]);
    }
}
