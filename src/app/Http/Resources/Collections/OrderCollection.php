<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\Order */
class OrderCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta'=>[
                'current_page'=>$this->currentPage(),
                'last_page'=>$this->lastPage(),
                'per_page' => $this->perPage(),
                'total' => $this->total(),
            ]
        ];
    }
}
