<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\User;
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
    }

    protected function tearDown(): void
    {
        $this->user->delete();
        parent::tearDown(); // Важно вызывать родительский метод
    }

    public function testBasic()
    {


        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
