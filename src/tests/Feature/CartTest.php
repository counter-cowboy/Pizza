<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CartTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->userToken = JWTAuth::fromUser($this->user);
        $this->adminToken = JWTAuth::fromUser($this->admin);
        $this->cart = Cart::factory()->create();
        $this->products = [1, 3, 5, 6];
    }

    protected function tearDown(): void
    {
        $this->user->delete();
        $this->cart->delete();
        parent::tearDown();
    }

    public function testCartIndexForAdminSuccessExpectHttp_200()
    {
        $response = $this->withToken($this->adminToken)
            ->get(route('carts.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testCartIndexForUserFailedExpectHttp_403()
    {
        $response = $this->withToken($this->userToken)
            ->get(route('carts.index'));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testCartStoreForNonAuthenticatedSuccessExpectHttp_201()
    {
        $response = $this->postJson(route('carts.store'), ['product_id' => 2]);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function testCartStoreForAuthenticatedSuccessExpectHttp_201()
    {
        $response = $this->withToken($this->userToken)
            ->postJson(route('carts.store'), ['product_id' => 2]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function testCartShowForUserSuccessExpect_200()
    {
        $user=User::factory()->create();
        $userToken = JWTAuth::fromUser($user);
        $this->withToken($userToken)
            ->postJson(route('carts.store', ['product_id' => 3]));

        $this->withToken($userToken)
            ->getJson(route('carts.show', Auth::user()->cart->id))
            ->assertStatus(Response::HTTP_OK);
    }
}
