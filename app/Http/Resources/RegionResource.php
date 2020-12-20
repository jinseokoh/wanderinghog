<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'name' => __("regions.{$this->slug}"),
            'slug' => $this->slug,
            'depth' => $this->depth,
        ];
    }
}
