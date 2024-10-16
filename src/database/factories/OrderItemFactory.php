<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrderItemFactory extends Factory
{
    protected $model = OrderProduct::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'odrer_id' => $this->faker->randomNumber(),
            'quantity' => $this->faker->randomNumber(),

            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'product_id' => Product::factory(),
        ];
    }
}
