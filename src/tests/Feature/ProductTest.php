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
        $response = $this->get(route('products.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testProductsIndexSuccessSearchProductByCategory()
    {
        $response = $this->get(route('products.index', ['category' => 1]));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testProductsIndexFailedSearchProductInvalidCategoryHttp422()
    {
        $response = $this->get(route('products.index', ['category' => 3]));

        $response->assertJsonFragment(['category' => ['The selected category is invalid.']]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    public function testProductStoreSuccessForAdmin()
    {
        $token = JWTAuth::fromUser(User::factory()->create(['is_admin' => true]));

        $response = $this->withToken($token)
            ->postJson(route('products.store', Product::factory()->create()->toArray()));

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function testProductStoreFailedForUser()
    {
        $token = JWTAuth::fromUser(User::factory()->create());

        $response = $this->withToken($token)
            ->postJson(route('products.store', Product::factory()
                ->create()->toArray()));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testProductStoreFailedForAdminEmptyNameAndStringPriceExpectHttp422()
    {
        $response = $this->withToken(JWTAuth::fromUser(User::factory()->create(['is_admin' => true])))
            ->postJson(route('products.store', [
                'name' => '',
                'description' => fake()->text(20),
                'price' => 'qwerty',
                'image' => fake()->url,
                'category_id' => 2
            ]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonFragment(['name' => ['The name field is required.']]);
        $response->assertJsonFragment(['price' => ['The price field must be a number.']]);
    }

    public function testProductShowSuccessForAll()
    {
        $response = $this->get(route('products.show', 3));
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testProductUpdateSuccessForAdmin()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withToken($token)
            ->patch(
                route('products.update', 1),
                Product::factory()->create()->toArray()
            );

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testProductUpdateSuccessForUser()
    {
        $response = $this->withToken(JWTAuth::fromUser(User::factory()->create()))
            ->patchJson(
                route('products.update', 1),
                Product::factory()->create()->toArray()
            );

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testProductDestroySuccessForAdmin()
    {
        $product = Product::factory()->create();

        $response = $this->withToken(JWTAuth::fromUser(User::factory()->create(['is_admin' => true])))
            ->delete(route('products.destroy', $product->id));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testProductDestroySuccessForUser()
    {
        $product = Product::factory()->create();

        $response = $this->withToken(JWTAuth::fromUser(User::factory()->create()))
            ->delete(route('products.destroy', $product->id));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
