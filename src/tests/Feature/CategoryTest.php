<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategoryTest extends TestCase
{
    private User $user;
    private User $admin;
    private array $category;
    private string $userToken;
    private string $adminToken;

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

    public function testCategoryIndexSuccessForAllExpectHttpOk()
    {
        $response = $this->get(route('categories.index'));
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testCategoryStoreFailedForAdminWithEmptyCategoryNameExpectHttpUnprocessableEntity()
    {
        $response = $this->withToken($this->adminToken)
            ->postJson(route('categories.store'), ['name' => '']);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['name' => ['The name field is required.']]);
    }

    public function testCategoryStoreFailedForNonAuthorizedExpectHttpForbidden()
    {
        $response = $this->withToken($this->userToken)
            ->postJson(
                route('categories.store'),
                $this->category
            );
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testCategoryStoreSuccessForAdminExpectHttpCreated()
    {
        $this->withToken($this->adminToken)
            ->postJson(route('categories.store'), $this->category)
            ->assertStatus(Response::HTTP_CREATED);
    }

    public function testCategoryShowSuccessForAllExpectHttpOk()
    {
        $this->get('/api/categories/1')
            ->assertStatus(Response::HTTP_OK);
    }

    public function testCategoryUpdateFailedForAdminWithEmptyCategoryNameExpectHttpUnprocessableEntity()
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

    public function testCategoryUpdateFailedForNonAuthorizedExpectHttpForbidden()
    {
        $this->withToken($this->userToken)
            ->patchJson(
                route('categories.update', 1),
                $this->category
            )
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
