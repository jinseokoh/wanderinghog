<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreferenceResource extends JsonResource
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
            'ready' => $this->ready,
            'min_age' => $this->min_age,
            'max_age' => $this->max_age,
            'min_height' => $this->min_height,
            'max_height' => $this->max_height,
            'gender' => $this->gender,
            'smoking' => $this->smoking,
            'drinking' => $this->drinking,

            'notifications' => $this->notifications,

            'appearances' => $this->appearances,
            'careers' => $this->careers,
            'interests' => $this->interests,
            'personalities' => $this->personalities,
        ];
    }
}
