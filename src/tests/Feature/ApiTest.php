<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiTest extends TestCase
{
    public function testUsersIndex(): void
    {
        $response = $this->get('/api/users');
        $response->assertOk();
//        $response->assertStatus(201);
    }
}
