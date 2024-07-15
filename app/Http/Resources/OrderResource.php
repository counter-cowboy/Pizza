<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Order */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'user_id' => $this->user_id,
            'status_id' => $this->status_id,
            'total_price' => $this->total_price,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'delivery_time' => $this->delivery_time,
        ];
    }
}
