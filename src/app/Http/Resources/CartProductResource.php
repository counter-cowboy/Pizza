<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\CartProduct */
class CartProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'quantity' => $this->quantity,

            'cart_id' => $this->cart_id,
            'product_id' => $this->product_id,

            'cart' => new CartResource($this->whenLoaded('cart')),
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
