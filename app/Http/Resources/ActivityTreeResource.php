<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ActivityTreeResource extends ActivityResource
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

        return array_merge(parent::toArray($request), [
            'id' => $this->id,
//            'parent' => $this->whenPivotLoaded('category_product', function () {
//                return $this->pivot->parent;
//            }),
            'children' => ActivityTreeResource::collection(
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
