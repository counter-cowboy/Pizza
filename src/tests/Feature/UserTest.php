<?php

namespace Tests\Feature;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->user = User::factory()->create();
        $this->adminToken = JWTAuth::fromUser($this->admin);
        $this->userToken = JWTAuth::fromUser($this->user);
    }

    protected function tearDown(): void
    {
        $this->user->delete();
        $this->admin->delete();
        parent::tearDown();
    }

    public function testUsersIndexSuccessForAdmin()
    {
        $this->withToken($this->adminToken)
            ->getJson(route('users.index'))
            ->assertStatus(Response::HTTP_OK);
    }

    public function testUsersIndexFailedForNotAdmin()
    {
        $this->withToken($this->userToken)
            ->getJson(route('users.index'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
