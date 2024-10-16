<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CartRequest extends FormRequest
{
    public function rules(): array
    {

        return [
            'product_id' => ['required',
                Rule::unique('cart_product')
                    ->where(function ($query) {
                        return $query->where('cart_id', Auth::user()->cart->id);
                    })]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'product_id.unique' => 'Already exists in cart'
        ];
    }
}
