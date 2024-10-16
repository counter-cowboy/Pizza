<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'cart_id' => ['required', 'exists:carts'],
            'product_id' => ['required', 'exists:products'],
            'quantity' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
