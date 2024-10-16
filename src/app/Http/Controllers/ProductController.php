<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductSearchRequest;
use App\Http\Resources\Collections\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Request;

class ProductController extends Controller
{
    public function index(ProductSearchRequest $request, ProductService $service): AnonymousResourceCollection
    {
        return  ProductResource::collection($service->search($request));
    }

    public function store(ProductRequest $request): ProductResource
    {
        $this->authorize('isAdmin', Product::class);
        return new ProductResource(Product::create($request->validated()));
    }

    public function show(Product $product)
    {
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
