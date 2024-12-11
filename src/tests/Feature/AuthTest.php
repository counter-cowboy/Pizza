<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->userData = [
            'name' => 'testUser',
            'email' => '2@2.qw',
            'password' => '123456'
        ];
        $this->user = User::create($this->userData);
    }

    protected function tearDown(): void
    {
        $this->user->delete();
        parent::tearDown();
    }

    public function testLoginSuccessExpectHttp200()
    {
        $this->postJson(route('login'), $this->userData)
            ->assertStatus(200);
    }

    public function testLoginFailInvalidEmailExpectHttp401()
    {
        $this->postJson(route('login'), [
            'email' => '1112@2.qw',
            'password' => '123456'
        ])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testLoginFailInvalidPasswordExpect401()
    {
        $this->postJson(route('login'), [
            'email' => '2@2.qw',
            'password' => '12345678'
        ])->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testLogoutSuccessExpectHttp200()
    {
        $this->withToken(JWTAuth::fromUser($this->user))
            ->postJson(route('logout'))
            ->assertStatus(Response::HTTP_OK);
    }

    public function testLogoutFailWithoutTokenExpectHttp401()
    {
        $this->postJson(route('logout'))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testRefreshTokenSuccessExpectHttp200()
    {
        $this->withToken(JWTAuth::fromUser($this->user))
            ->postJson(route('refresh'))
            ->assertStatus(Response::HTTP_OK);
    }
}
