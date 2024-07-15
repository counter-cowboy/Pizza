<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Item;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'quantity' => $this->faker->randomNumber(),

            'cart_id' => Cart::factory(),
            'product_id' => Product::factory(),
            'cart_id' => Cart::factory(),
            'product_id' => Product::factory(),
        ];
    }
}
