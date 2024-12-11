<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'total_amount' => $this->faker->randomFloat(2, 1, 200),
            'status' => $this->faker->randomElement(['in_progress', 'delivering']),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'delivery_time' => Carbon::now()->addHours(2)->addMinutes(35)->toDateTimeString(),

            'user_id' => User::factory(),
        ];
    }

    public function configure(): OrderFactory
    {
        return $this->afterCreating(function (Order $order) {

            $products = Product::inRandomOrder()->take(6)->get();

            foreach ($products as $product) {
                $order->product()->attach($product->id, [
                    'quantity' => rand(1, 5),
                    'created_at' => now()
                ]);
            }
        });
    }
}
