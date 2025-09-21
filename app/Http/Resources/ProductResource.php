<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'ingredients' => $this->ingredients ?? [],
            'images'      => $this->getMedia('products')->map(fn ($media) => [
                'id'  => $media->id,
                'url' => $media->getUrl('preview'),
                'original_url' => $media->getUrl(),
            ]),
            'total_grams' => $this->total_grams,
        ];
    }
}
