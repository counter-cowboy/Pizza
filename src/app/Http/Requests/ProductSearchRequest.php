<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductSearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category' => 'sometimes|integer|exists:categories,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

}
