<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;


class ProductTest extends TestCase
{
    use WithFaker;
    public function testProductsIndexSuccessForAll()
    {
        $response = $this->get('/api/products');

        $response->assertStatus(200);
    }
    public function testProductStoreSuccessForAdmin()
    {
        $user = User::factory()->create(['is_admin'=>true]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])
            ->post('api/products', Product::factory()->create()->toArray());

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function testProductStoreFailedForUser()
    {
        $user = User::factory()->create();

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])
            ->post('api/products', Product::factory()->create()->toArray());

        $response->assertStatus(403);
    }

    public function createProduct(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->text(30),
            'price' => fake()->randomFloat(2, 5, 255),
            'image' => fake()->url,
            'category_id' => fake()->randomElement([1, 2]),
        ];
    }

    public function sum(int $a, int $b)
    {
        return $a+$b;
    }

}
