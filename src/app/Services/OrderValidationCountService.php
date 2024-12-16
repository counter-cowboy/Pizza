<?php

namespace App\Services;

use App\Models\Product;

class OrderValidationCountService
{
    public function validateProductCount(array $data): array
    {
        $pizzas = 0;
        $drinks = 0;
        $errorArr = [];
        foreach ($data as $product) {
            $productId = $product['product_id'];
            $quantity = $product['quantity'];

            $productData = Product::findOrFail($productId);

            if ($productData->category->name == 'pizza') {
                $pizzas += $quantity;

            } elseif ($productData->category->name == 'drink') {
                $drinks += $quantity;
            }

            if ($pizzas > 20) {
                $errorArr[] = ['pizzas' => 'You can order no more than 10 pizzas'];
            }
            if ($drinks > 10) {
                $errorArr[] = ['drinks' => 'You can order no more than 10 drinks'];
            }
        }
        return $errorArr;
    }
}
