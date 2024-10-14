<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users',
            'product_id'=>'required'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
    public function messages(): array
    {
        return [
            'user_id.required'=>'UserId required',
            'user_id.exists'=>'Error, duplicate user_id'
        ];
    }

}
