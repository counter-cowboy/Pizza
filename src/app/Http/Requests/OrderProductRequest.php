<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'odrer_id' => ['required', 'integer'],
            'order_id' => ['required', 'exists:orders'],
            'product_id' => ['required', 'exists:products'],
            'product_id' => ['required', 'exists:products'],
            'quantity' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
