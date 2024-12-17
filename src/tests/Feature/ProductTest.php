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
    private User $admin;
    private User $user;
    private Product $product;
    private string $userToken;
    private string $adminToken;


    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->admin = User::factory()->create(['is_admin' => true]);

        $this->userToken = JWTAuth::fromUser($this->user);
        $this->adminToken = JWTAuth::fromUser($this->admin);
        $this->product = Product::factory()->create();
    }

    public function testProductsIndexSuccessForAllExpectHttpOk()
    {
        $this->getJson(route('products.index'))
            ->assertStatus(Response::HTTP_OK);
    }

    public function testProductsIndexSuccessSearchProductByCategoryExpectHttpOk()
    {
        $this->getJson(route('products.index', ['category' => 1]))
            ->assertStatus(Response::HTTP_OK);
    }

    public function testProductsIndexFailedSearchProductInvalidCategoryExpectHttpUnprocessableEntity()
    {
        $this->getJson(route('products.index', ['category' => 3333]))
            ->assertJsonFragment(['category' => ['The selected category is invalid.']])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testProductStoreSuccessForAdminExpectHttpCreated()
    {
        $this->withToken($this->adminToken)
            ->postJson(route('products.store', $this->product->toArray()))
            ->assertStatus(Response::HTTP_CREATED);
    }

    public function testProductStoreFailedForUserExpectHttp_403()
    {
        $this->withToken($this->userToken)
            ->postJson(route('products.store', $this->product->toArray()))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testProductStoreFailedForAdminEmptyNameAndStringPriceExpectHttpUnprocessableEntity()
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

    public function testProductShowSuccessForAllExpectHttpOk()
    {
        $this->get(route('products.show', $this->product->id))
            ->assertStatus(Response::HTTP_OK);
    }

    public function testProductShowFailForAllExpectHttpNotFound()
    {
        $this->get(route('products.show', 20000))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testProductUpdateSuccessForAdminExpectHttpOk()
    {
        $this->withToken($this->adminToken)
            ->patch(route('products.update', $this->product->id), $this->product->toArray())
            ->assertStatus(Response::HTTP_OK);
    }

    public function testProductUpdateSuccessForUserExpectHttpForbidden()
    {
        $this->withToken($this->userToken)
            ->patchJson(route('products.update', 1), $this->product->toArray())
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testProductDestroySuccessForAdminExpectHttpOk()
    {
        $this->withToken($this->adminToken)
            ->delete(route('products.destroy', $this->product->id))
            ->assertStatus(Response::HTTP_OK);
    }

    public function testProductDestroySuccessForUserExpectHttpForbidden()
    {
        $this->withToken($this->userToken)
            ->delete(route('products.destroy', $this->product->id))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    protected function tearDown(): void
    {
        $this->user->delete();
        $this->admin->delete();
        parent::tearDown();
    }
}
