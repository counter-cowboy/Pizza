<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Generator;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $items = ['pizza', 'drink'];
        return [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'name' => $items[array_rand($items)],
        ];
    }
}
