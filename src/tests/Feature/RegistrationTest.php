<?php

namespace Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function testRegistrationSuccess()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post('/api/register', [
            'name' => fake()->name,
            'email' => fake()->email,
            'password' => fake()->password(6, 10)
        ]);

        $response->assertStatus(201);
    }

    public function testRegistrationFailedNoName()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post('/api/register', [
            'name' => '',
            'email' => fake()->email,
            'password' => fake()->password(6, 10)
        ]);

        $response->assertStatus(422);
    }

    public function testRegistrationFailedNoEmail()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post('/api/register', [
            'name' => fake()->name,
            'email' => fake()->email,
            'password' => ''
        ]);

        $response->assertStatus(422);
    }

    public function testRegistrationFailedShortPassword()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post('/api/register', [
            'name' => fake()->name,
            'email' => fake()->email,
            'password' => fake()->password(3, 5)
        ]);

        $response->assertStatus(422);
    }

    public function testRegistrationFailedNoPassword()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post('/api/register', [
            'name' => fake()->name,
            'email' => fake()->email,
            'password' => ''
        ]);

        $response->assertStatus(422);
    }
    public function testRegistrationFailedIncorrectEmail()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post('/api/register', [
            'name' => fake()->name,
            'email' => fake()->word,
            'password' => fake()->password(6, 10)
        ]);

        $response->assertStatus(422);
    }


}
