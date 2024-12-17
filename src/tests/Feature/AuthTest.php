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
    private array $userData;
    private User $user;
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

    public function testLoginSuccessExpectHttpOk()
    {
        $this->postJson(route('login'), $this->userData)
            ->assertStatus(200);
    }

    public function testLoginFailInvalidEmailExpectHttpUnprocessableEntity()
    {
        $this->postJson(route('login'), [
            'email' => '1112@2.qw',
            'password' => '123456'
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testLoginFailNoEmailExpectHttpUnprocessableEntity()
    {
        $this->postJson(route('login'), [
//            'email' => null,
            'password' => '123456'
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testLoginFailInvalidPasswordExpectUnprocessableEntity()
    {
        $this->postJson(route('login'), [
            'email' => '2@2.qw',
            'password' => '12345678'
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testLogoutSuccessExpectHttpOk()
    {
        $this->withToken(JWTAuth::fromUser($this->user))
            ->postJson(route('logout'))
            ->assertStatus(Response::HTTP_OK);
    }

    public function testLogoutFailWithoutTokenExpectHttpUnauthorized()
    {
        $this->postJson(route('logout'))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testRefreshTokenSuccessExpectHttpOk()
    {
        $this->withToken(JWTAuth::fromUser($this->user))
            ->postJson(route('refresh'))
            ->assertStatus(Response::HTTP_OK);
    }
}
