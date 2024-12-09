<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Validation\Validator;

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
            'products.*.product_id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1']
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
