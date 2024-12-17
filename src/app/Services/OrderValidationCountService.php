<?php

namespace App\Services;

use App\Models\Product;

class OrderValidationCountService
{
    private const MAX_PIZZA_COUNT=20;
    private const MAX_DRINK_COUNT=10;
    public function validateProductCount(array $data): array
    {
        $pizzas = 0;
        $drinks = 0;
        $errorArr = [];


        foreach ($data as $product) {

            $productId = $product['product_id'];
            $quantity = $product['quantity'];

            $productData = Product::findOrFail($productId);

            if ($productData->category->name === 'pizza') {
                $pizzas += $quantity;

            } elseif ($productData->category->name === 'drink') {
                $drinks += $quantity;
            }

            if ($pizzas > self::MAX_PIZZA_COUNT) {
                $errorArr[] = ['pizzas' => 'You can order no more than 10 pizzas'];
            }
            if ($drinks > self::MAX_DRINK_COUNT) {
                $errorArr[] = ['drinks' => 'You can order no more than 10 drinks'];
            }
        }
        return $errorArr;
    }
}
