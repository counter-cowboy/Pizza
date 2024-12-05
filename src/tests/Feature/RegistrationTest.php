<?php

namespace Tests\Feature;

use DB;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use WithFaker;

    public function testRegistrationSuccess()
    {
//        $dbName=DB::connection()->getDatabaseName();
//        dd($dbName);
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])
            ->post('/api/register', [
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
        ])
            ->post('/api/register', [
            'name' => '',
            'email' => fake()->email,
            'password' => fake()->password(6, 10)
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function testRegistrationFailedNoEmail()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post('/api/register', [
            'name' => fake()->name,
            'email' => '',
            'password' => fake()->password(6, 10)
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
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
        $response->assertJsonValidationErrors('password');
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
        $response->assertJsonValidationErrors('password');
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
        $response->assertJsonValidationErrors('email');
    }


}
