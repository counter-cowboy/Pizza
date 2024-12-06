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

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }



}
