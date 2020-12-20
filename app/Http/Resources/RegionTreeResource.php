<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegionTreeResource extends RegionResource
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

        return array_merge(parent::toArray($request), [
            'children' => RegionTreeResource::collection(
                $this->when(
                    $this->relationLoaded('children') && $this->children->count(),
                    function () {
                        return $this->children;
                    }
                )
            ),
        ]);
    }
}
