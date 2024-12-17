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
        $this->withToken($this->userToken)
            ->postJson(route('orders.store', $this->order))
            ->assertStatus(Response::HTTP_CREATED);
    }

    public function testOrderStoreForUserFailedExpectHttpUnprocessableEntity()
    {
        $this->withToken($this->userToken)
            ->postJson(route('orders.store', [
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
                        'quantity' => 40
                    ],
                    [
                        'product_id' => 5,
                        'quantity' => 223
                    ]
                ],
                'user_id' => $this->user->id,
            ]))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
//            ->assertJsonFragment(['pizzas' => 'You can order no more than 10 pizzas'])
            ->assertJsonFragment(['drinks' => 'You can order no more than 10 drinks']);
    }

    public function testOrderStoreForUnauthorizedFailExpectHttp401()
    {
        $this->postJson(route('orders.store'), $this->order)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testOrderShowForUserSuccessExpectHttp200()
    {
        $order = $this->user->order()->create($this->order);

        $this->withToken($this->userToken)
            ->getJson(route('orders.show', $order->id))
            ->assertStatus(Response::HTTP_OK);
    }

    public function testOrderUpdateForUserSuccessExpectHttpOk()
    {
        $order = $this->user->order()->create($this->order);

        $this->withToken(JWTAuth::fromUser($this->user))
            ->patchJson(
                route('orders.update', $order->id),
                [
                    'products' => [
                        [
                            'product_id' => 2,
                            'quantity' => 4
                        ],
                        [
                            'product_id' => 5,
                            'quantity' => 2
                        ]
                    ],
                ]
            )
            ->assertStatus(Response::HTTP_OK);
    }

    public function testOrderUpdateForUserFailExceedMaxCountExpectHttpUnprocessableEntity()
    {
        $order = $this->user->order()->create($this->order);

        $this->withToken(JWTAuth::fromUser($this->user))
            ->patchJson(
                route('orders.update', $order->id),
                [
                    'products' => [
                        [
                            'product_id' => 2,
                            'quantity' => 25
                        ],
                        [
                            'product_id' => 5,
                            'quantity' => 25
                        ]
                    ],
                ]
            )
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
//        ->assertJsonFragment(['pizzas' => 'You can order no more than 10 pizzas'])
            ->assertJsonFragment(['drinks' => 'You can order no more than 10 drinks']);
    }

    public function testOrderCancelFofUserSuccessExpectHttp200()
    {
        $order = $this->user->order()->create($this->order);

        $this->withToken($this->userToken)
            ->postJson(route('orders.cancel', $order->id))
            ->assertStatus(Response::HTTP_OK);
    }


}
