<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'slug'       => $this->slug,
            'description'=> $this->description,
            'image'      => url($this->image),
            'price'      => $this->price,
            'quantity'  => $this->quantity,
            'condition'  => $this->condition,
            'discount_in_percentage'  => $this->discount_in_percentage,
            'total_price'  => $this->total_price,
            'status'  => $this->status,
            'brand'      => $this->relationLoaded('brand') && !($this->brand->isEmpty()) ? BrandResource::collection($this->brand) : null,
            'category'   =>   new CategoryResource($this->category),
            'sub_category'=>  new SubCategoryResource($this->subCategory) ?? null ,
            'created_at' => $this->created_at->format('l, d M Y'),
        ];
    }
}
