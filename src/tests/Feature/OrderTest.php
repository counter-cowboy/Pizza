<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Database\Factories\OrderFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->userToken = JWTAuth::fromUser($this->user);
        $this->adminToken = JWTAuth::fromUser($this->admin);

        $this->order = [
//            'created_at' => Carbon::now(),
//            'updated_at' => Carbon::now(),
            'total_amount' => $this->faker->randomFloat(2, 1, 200),
            'status' => $this->faker->randomElement(['in_progress', 'delivering',]),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'delivery_time' => Carbon::now()->addHours(2)
                ->toDateTimeString(),

            'products' => [
                [
                    'product_id' => 3,
                    'quantity' => 4
                ],
                [
                    'product_id' => 5,
                    'quantity' => 2
                ]
            ],
            'user_id' => $this->user->id,
        ];
    }

    protected function tearDown(): void
    {
        $this->user->delete();
        $this->admin->delete();
        parent::tearDown();
    }

    public function testOrderIndexForAdminSuccessExpectHttp_200()
    {
        $this->withToken($this->adminToken)
            ->get(route('orders.index'))->assertStatus(200);
    }

    public function testOrderIndexForUserSuccessExpectHttp_200()
    {
        $this->withToken($this->userToken)
            ->postJson(route('orders.store', $this->order));

        $this->withToken($this->userToken)
            ->getJson(route('orders.index'))
            ->assertStatus(Response::HTTP_OK);
    }

    public function testOrderStoreForUserSuccessExpectHttp_201()
    {
        $response = $this->withToken($this->userToken)
            ->postJson(route('orders.store', $this->order));

        $response->assertStatus(Response::HTTP_CREATED);
    }


}
