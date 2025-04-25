<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OrderDetailResource;
use App\Http\Resources\OrderItemResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'status' => $this->status,
            'shipping_charges' => $this->shipping_charges,
            'tax' => $this->tax,
            'discount' => $this->discount,
            'total_price' => $this->total_price,
            'created_at' => $this->created_at->toDateTimeString(),
            'customer_details' => new OrderDetailResource($this->whenLoaded('details')),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
