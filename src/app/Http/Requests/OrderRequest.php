<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
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

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'Message' => 'Validation error',
            'data' => [
                'errors' => $validator->errors()
            ]
        ], 422));

    }
}
