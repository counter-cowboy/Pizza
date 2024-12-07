<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'price' => $this->faker->randomFloat(2, 5, 255),
            'image' => $this->faker->filePath(),
            'category_id' => Category::all()->random()->id
        ];
    }
    public function configure(): ProductFactory
    {
        return $this->afterCreating(function (Product $product) {

            $carts = Cart::factory(5)->create();


            $product->cart()->attach($carts->pluck('id'), ['created_at' => now()]);
        });
    }
}
