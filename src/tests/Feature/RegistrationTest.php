<?php

namespace Tests\Feature;

use DB;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use WithFaker;

    public function testRegistrationSuccess()
    {
        $response = $this->postJson(route('register'), [
            'name' => fake()->name,
            'email' => fake()->email,
            'password' => fake()->password(6, 10)
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function testRegistrationFailedNoName()
    {
        $response = $this->postJson(route('register'), [
            'name' => '',
            'email' => fake()->email,
            'password' => fake()->password(6, 10)
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('name');
    }

    public function testRegistrationFailedNoEmail()
    {
        $response = $this
            ->postJson(route('register'), [
                'name' => fake()->name,
                'email' => '',
                'password' => fake()->password(6, 10)
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('email');
    }

    public function testRegistrationFailedShortPassword()
    {
        $response = $this->postJson(route('register'), [
            'name' => fake()->name,
            'email' => fake()->email,
            'password' => fake()->password(3, 5)
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('password');
    }

    public function testRegistrationFailedNoPassword()
    {
        $response = $this->postJson(route('register'), [
            'name' => fake()->name,
            'email' => fake()->email,
            'password' => ''
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('password');
    }

    public function testRegistrationFailedIncorrectEmail()
    {
        $response = $this->postJson('/api/register', [
            'name' => fake()->name,
            'email' => fake()->word,
            'password' => fake()->password(6, 10)
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('email');
    }
}
