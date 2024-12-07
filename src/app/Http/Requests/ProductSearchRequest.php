<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
