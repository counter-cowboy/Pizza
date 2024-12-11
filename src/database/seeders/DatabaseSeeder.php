<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Category::factory(2)->create();

        Product::factory(10)->create();

        User::factory(30)
            ->has(Cart::factory())
            ->has(Order::factory())
            ->create();
    }
}
