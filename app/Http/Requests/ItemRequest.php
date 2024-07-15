<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'cart_id' => ['required', 'exists:carts'],
            'product_id' => ['required', 'exists:products'],
            'quantity' => ['required', 'integer'],
            'cart_id' => ['required', 'exists:carts'],
            'product_id' => ['required', 'exists:products'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
