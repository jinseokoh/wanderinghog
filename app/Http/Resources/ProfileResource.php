<?php

namespace App\Http\Resources;

use App\Enums\ProfessionEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'profession_type' => $this->profession_type,
            'profession_name' => $this->profession_type ? __('professions.'.(new ProfessionEnum($this->profession_type))->value) : null,
            'profession' => $this->profession,
            'profession_verified_at' => $this->profession_verified_at,
            'school' => $this->school,
            'status' => $this->status,

            'height' => $this->height,
            'vehicle' => $this->vehicle,
            'smoking' => $this->smoking,
            'drinking' => $this->drinking,

            'level' => $this->level,
            'limit' => $this->limit,
            'coins' => $this->coins,

            'intro' => $this->intro,
        ];
    }
}
