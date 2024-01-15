<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Category */
class CategoryResource extends JsonResource
{
  public function toArray(Request $request)
  {
    return [
      'id' => $this->id,
      'name' => $this->whenHas('name', $this->name),
      'parent_category' => CategoryResource::make($this->whenLoaded('parentCategory')),
    ];
  }
}
