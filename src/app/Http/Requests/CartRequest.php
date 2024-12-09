<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CartRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => ['required',
//                Rule::unique('cart_product')
//                    ->where(function ($query) {
//
//                        $user = auth()->user();
//
//                        return $query->where('cart_id', $user->cart->id);
//                    })
            ]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'Message' => 'Validation error',
            'data' => [
                'errors' => $validator->errors()
            ]
        ], 422));

    }
}
