<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
//            'user_id' => ['required', 'exists:users'], //нужен ли?
//            'total_amount' => ['required', 'numeric'], // сумму считаем по итогу
//            'status' => ['required'],
            'address' => ['required'],
            'phone' => ['required'],
            'email' => ['required', 'email', 'max:254'],
            'delivery_time' => ['required', 'date'],
            'products'=>'array|required'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
