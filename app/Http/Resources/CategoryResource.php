<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SubCategoryResource;
class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'image'      => url($this->image),
            'slug'=>$this->slug,
            'description'=>$this->description,
            'sub_categories'=>  $this->relationLoaded('subCategories') &&!($this->subCategories->isEmpty()) ? SubCategoryResource::collection($this->subCategories):null,
        ];
    }
}
