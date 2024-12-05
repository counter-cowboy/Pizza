<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserTest extends TestCase
{
    public function testUsersIndexSuccessForAdmin()
    {
        $user = User::create([
            'name' => 'VasyaAdmin',
            'email' => fake()->email(),
            'password' => 123456,
            'is_admin' => 1
        ]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            "Authorization" => 'Bearer' . $token,

        ])->get('/api/users');

        $response->assertStatus(200);
    }

    public function testUsersIndexFailedForNotAdmin()
    {
        $user = User::create([
            'name' => 'Vasya',
            'email' => fake()->email(),
            'password' => 123456,
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            "Authorization" => 'Bearer' . $token,

        ])->get('/api/users');

        $response->assertStatus(403);
    }
}
