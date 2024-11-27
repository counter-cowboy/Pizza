<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CartProductFactory extends Factory
{
    protected $model = CartProduct::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'quantity' => $this->faker->numberBetween(1, 5),

            'cart_id' => Cart::factory(),
            'product_id' => Product::factory(),
        ];
    }

}
