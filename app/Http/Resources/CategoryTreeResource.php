<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryTreeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            'parent_id' => $this->parent_id,
            'children' => CategoryTreeResource::collection(
                $this->when(
                    $this->relationLoaded('children') && $this->children->count(),
                    function () {
                        return $this->children;
                    }
                )
            ),
        ];
    }
}
