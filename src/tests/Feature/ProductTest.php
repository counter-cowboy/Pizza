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

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->admin = User::factory()->create(['is_admin' => true]);

        $this->userToken = JWTAuth::fromUser($this->user);
        $this->adminToken = JWTAuth::fromUser($this->admin);
        $this->product = Product::factory()->create();
    }

    public function testProductsIndexSuccessForAllExpectHttp_200()
    {
        $response = $this->get(route('products.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testProductsIndexSuccessSearchProductByCategoryExpectHttp_200()
    {
        $response = $this->get(route('products.index', ['category' => 1]));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testProductsIndexFailedSearchProductInvalidCategoryExpectHttp_422()
    {
        $response = $this->get(route('products.index', ['category' => 3]));

        $response->assertJsonFragment(['category' => ['The selected category is invalid.']])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testProductStoreSuccessForAdminExpectHttp_201()
    {
        $response = $this->withToken($this->adminToken)
            ->postJson(route('products.store', $this->product->toArray()));

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function testProductStoreFailedForUserExpectHttp_403()
    {
        $response = $this->withToken($this->userToken)
            ->postJson(route('products.store', $this->product->toArray()));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testProductStoreFailedForAdminEmptyNameAndStringPriceExpectHttp_422()
    {
        $response = $this->withToken($this->adminToken)
            ->postJson(route('products.store', [
                'name' => '',
                'description' => fake()->text(20),
                'price' => 'qwerty',
                'image' => fake()->url,
                'category_id' => 2
            ]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['name' => ['The name field is required.']])
            ->assertJsonFragment(['price' => ['The price field must be a number.']]);
    }

    public function testProductShowSuccessForAllExpectHttp_200()
    {
        $response = $this->get(route('products.show', $this->product->id));
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testProductUpdateSuccessForAdminExpectHttp_200()
    {
        $response = $this->withToken($this->adminToken)
            ->patch(
                route('products.update', $this->product->id),
                $this->product->toArray()
            );

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testProductUpdateSuccessForUserExpectHttp_403()
    {
        $response = $this->withToken($this->userToken)
            ->patchJson(
                route('products.update', 1),
                $this->product->toArray()
            );

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testProductDestroySuccessForAdminExpectHttp_200()
    {
        $response = $this->withToken($this->adminToken)
            ->delete(route('products.destroy', $this->product->id));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testProductDestroySuccessForUserExpectHttp_403()
    {
        $response = $this->withToken($this->userToken)
            ->delete(route('products.destroy', $this->product->id));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    protected function tearDown(): void
    {
        $this->user->delete();
        $this->admin->delete();
        parent::tearDown();
    }
}
