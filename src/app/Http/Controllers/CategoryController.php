<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $this->authorize('canDo', Category::class);

        return CategoryResource::collection(Category::all());
    }

    public function store(CategoryRequest $request)
    {
        $this->authorize('canDo', Category::class);

        return new CategoryResource(Category::create($request->validated()));
    }

    public function show(Category $category)
    {
        $this->authorize('canDo', $category);

        return new CategoryResource($category);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $this->authorize('canDo', $category);

        $category->update($request->validated());

        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $this->authorize('canDo', $category);

        return response()->json($category->delete());
    }
}
