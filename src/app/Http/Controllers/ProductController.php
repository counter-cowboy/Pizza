<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\Collections\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $this->authorize('isAdmin', Product::class);
        return new ProductCollection(Product::paginate(10));
    }

    public function store(ProductRequest $request)
    {
        $this->authorize('isAdmin', Product::class);
        return new ProductResource(Product::create($request->validated()));
    }

    public function show(Product $product)
    {
        $this->authorize('isAdmin', Product::class);
        return new ProductResource($product);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $this->authorize('isAdmin', Product::class);
        $product->update($request->validated());

        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $this->authorize('isAdmin', Product::class);
        $product->delete();

        return response()->json();
    }
}
