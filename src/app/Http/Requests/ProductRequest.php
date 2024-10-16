<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'description' => ['required'],
            'price' => ['required', 'numeric'],
            'image' => ['required','string'],
            'category_id' => ['required', 'integer'],
        ];
    }
    public function messages(): array
    {
        return [
            'name.required'=>"Product name required",
            'description.required'=>'Describe the product',
            'image.required'=>'Attach picture for the product',
            'category_id'=>'Can not add product wothout category'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

}
