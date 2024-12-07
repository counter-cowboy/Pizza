<?php

namespace Tests\Feature;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserTest extends TestCase
{
    public function testUsersIndexSuccessForAdmin()
    {

        $response = $this->withToken(JWTAuth::fromUser(User::factory()->create(['is_admin' => true])))
            ->getJson(route('users.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testUsersIndexFailedForNotAdmin()
    {
        $response = $this->withToken(JWTAuth::fromUser(User::factory()->create()->toArray()))
            ->getJson(route('users.index'));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
