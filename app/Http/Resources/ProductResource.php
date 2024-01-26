<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'name' => $this->whenHas('name', $this->name),
      'description' => $this->whenHas('description', $this->description),
      'price' => $this->whenHas('price', $this->price),
      'image' => $this->whenHas('image', $this->image),
      'categories' => CategoryResource::collection($this->whenLoaded('categories')),
    ];
  }
}
