<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategoryTest extends TestCase
{
    public function testCategoryIndexSuccessForAllExpectHttp200()
    {
        $response = $this->get(route('categories.index'));
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testCategoryStoreFailedForAdminWithEmptyCategoryNameExpectHttp422()
    {
        $response = $this->withToken(JWTAuth::fromUser(User::factory()->create(['is_admin' => true])))
            ->postJson(route('categories.store'), ['name' => '']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonFragment(['name' => ['The name field is required.']]);
    }

    public function testCategoryStoreFailedForNonAuthorizedExpectHttp403()
    {
        $response = $this->withToken(JWTAuth::fromUser(User::factory()->create()))
            ->postJson(
                route('categories.store'),
                Category::factory()->create(['name' => 'drink'])->toArray()
            );
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testCategoryStoreSuccessForAdminExpectHttp201()
    {
        $response = $this->withToken(JWTAuth::fromUser(User::factory()->create(['is_admin' => true])))
            ->postJson(route('categories.store'), Category::create(['name' => 'drink'])->toArray());

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function testCategoryShowSuccessForAllExpectHttp200()
    {
        $response = $this->get('/api/categories/1');
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testCategoryUpdateFailedForAdminWithEmptyCategoryNameExpectHttp422()
    {
        $response = $this->withToken(JWTAuth::fromUser(User::factory()->create(['is_admin' => true])))
            ->patchJson(route('categories.update', 1), ['name' => '']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['name' => ['The name field is required.']]);
    }

    public function testCategoryUpdateSuccessForAdminExpectHttp200()
    {
        $response = $this->withToken(JWTAuth::fromUser(User::factory()->create(['is_admin' => true])))
            ->patchJson(route('categories.update', 5), ['name' => 'pizza']);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testCategoryUpdateFailedForNonAuthorizedExpectHttp403()
    {
        $response = $this->withToken(JWTAuth::fromUser(User::factory()->create()))
            ->patchJson(
                route('categories.update', 1),
                Category::factory()->create(['name' => 'drink'])->toArray()
            );
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

}
