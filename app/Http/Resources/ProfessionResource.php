<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'keyword' => $this->keyword,
            'title' => $this->getTranslation('title', app()->getLocale()),
            'uri' => $this->uri,
            'products' => ProductCurationResource::collection(
                $this->whenLoaded('products')
            )
        ];
    }
}
