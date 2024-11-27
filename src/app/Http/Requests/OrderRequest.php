<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Validator;

class OrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'address' => ['required'],
            'phone' => ['required'],
            'email' => ['required', 'email', 'max:254'],
            'delivery_time' => ['required', 'date'],
            'products' => ['array', 'required'],
            'products.*.product_id' => ['required', 'exists:products, id'],
            'products.*.quantity' => ['required', 'integer', 'min:1']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
