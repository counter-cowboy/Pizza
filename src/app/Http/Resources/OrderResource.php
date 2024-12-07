<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Order */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if (is_array($this->resource)) {
            return [
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'id' => $this->id,
                'total_amount' => $this->total_amount,
                'status' => $this->status,
                'address' => $this->address,
                'phone' => $this->phone,
                'email' => $this->email,
                'delivery_time' => $this->delivery_time,

                'user_id' => $this->user_id,
            ];
        }

    }
}
