<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'customer_name' => $this->customer_name,
            'email' => $this->email,
            'phone' => $this->phone,

            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,

            'payment_method' => $this->payment_method,

            'subtotal' => $this->subtotal,
            'shipping' => $this->shipping,
            'total' => $this->total,

            'status' => $this->status,

            'items' => OrderItemResource::collection(
                $this->whenLoaded('items')
            ),

            'created_at' => $this->created_at,
        ];
    }
}