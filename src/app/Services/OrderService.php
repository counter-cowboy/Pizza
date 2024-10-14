<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;

class OrderService
{
    public function store(int $user_id, array $data): array
    {
        $products[] = $data['products'];
        $total_amount = 0;
        $orderToCreate = [
            'user_id' => $user_id,
            'status' => 'in_progress',
            'phone' => $data['phone'],
            'email' => $data['email'],
            'address' => $data['address'],
            'delivery_time' => $data['delivery_time']
        ];

        $order = Order::firstOrCreate($orderToCreate);

        $productsToReturn = [];

        foreach ($products as $product) {
            $prod_id = $product['product_id'];
            $quantity = $product['quantity'];

            $some_product = Product::where('id', $prod_id)->first();
            $total_amount += $some_product->price * $quantity;
            $order->product()->attach($prod_id, ['quantity' => $quantity]);
            $productsToReturn[] = [
                'id' => $prod_id,
                'description' => $some_product->description,
                'image' => $some_product->image,
                'price' => $some_product->price,
                'quantity' => $quantity,
                'category' => $some_product->category->name
            ];
        }

        $order->total_amount = $total_amount;
        $order->save();

        $orderToCreate[] = $total_amount;
        $orderToCreate[] = $productsToReturn;


        return $orderToCreate;
    }

}
