<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer'],
            'status_id' => ['required', 'integer'],
            'total_price' => ['required', 'numeric'],
            'phone' => ['required'],
            'email' => ['required', 'email', 'max:254'],
            'address' => ['required'],
            'delivery_time' => ['required', 'date'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
