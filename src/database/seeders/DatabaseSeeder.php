<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        Category::factory(2)->create();
        Product::factory(20)->create();
        User::factory(80)
            ->has(Cart::factory())
            ->has(Order::factory())
            ->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
