<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->admin = User::factory()->create(['is_admin' => true]);

        $this->userToken = JWTAuth::fromUser($this->user);
        $this->adminToken = JWTAuth::fromUser($this->admin);

        $this->category = Category::factory()->create()->toArray();
    }

    protected function tearDown(): void
    {
        $this->user->delete();
        $this->admin->delete();
        parent::tearDown();
    }

    public function testCategoryIndexSuccessForAllExpectHttp200()
    {
        $response = $this->get(route('categories.index'));
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testCategoryStoreFailedForAdminWithEmptyCategoryNameExpectHttp422()
    {
        $response = $this->withToken($this->adminToken)
            ->postJson(route('categories.store'), ['name' => '']);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['name' => ['The name field is required.']]);
    }

    public function testCategoryStoreFailedForNonAuthorizedExpectHttp403()
    {
        $response = $this->withToken($this->userToken)
            ->postJson(
                route('categories.store'),
                $this->category
            );
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testCategoryStoreSuccessForAdminExpectHttp201()
    {
        $this->withToken($this->adminToken)
            ->postJson(route('categories.store'), $this->category)
            ->assertStatus(Response::HTTP_CREATED);
    }

    public function testCategoryShowSuccessForAllExpectHttp200()
    {
        $this->get('/api/categories/1')
            ->assertStatus(Response::HTTP_OK);
    }

    public function testCategoryUpdateFailedForAdminWithEmptyCategoryNameExpectHttp422()
    {
        $response = $this->withToken($this->adminToken)
            ->patchJson(
                route('categories.update', 1),
                ['name' => '']
            );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['name' => ['The name field is required.']]);
    }

    public function testCategoryUpdateSuccessForAdminExpectHttp200()
    {
        $this->withToken($this->adminToken)
            ->patchJson(route('categories.update', 5), ['name' => 'pizza'])
            ->assertStatus(Response::HTTP_OK);
    }

    public function testCategoryUpdateFailedForNonAuthorizedExpectHttp403()
    {
        $this->withToken($this->userToken)
            ->patchJson(
                route('categories.update', 1),
                $this->category
            )
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
