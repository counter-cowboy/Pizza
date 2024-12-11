<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class OrderUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['sometimes','exists:users'],
            'total_amount' => ['sometimes', 'numeric'],
            'status' => ['sometimes'],
            'address' => ['sometimes'],
            'phone' => ['sometimes'],
            'email' => ['sometimes', 'email', 'max:254'],
            'delivery_time' => ['sometimes', 'date'],
            'products' => ['array', 'sometimes'],
            'products.*.product_id' => ['sometimes', 'exists:products,id'],
            'products.*.quantity' => ['sometimes', 'integer', 'min:1']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'Message' => 'Validation error',
            'data' => [
                'errors' => $validator->errors()
            ]
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }

    public function authorize(): bool
    {
        return true;
    }
}
