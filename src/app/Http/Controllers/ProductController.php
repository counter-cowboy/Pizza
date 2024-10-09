<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        return ProductResource::collection(Product::all());
    }

    public function store(ProductRequest $request)
    {
        if ($request->user()->cannot('store')){
            abort(403);
        }
        return new ProductResource(Product::create($request->validated()));
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function update(ProductRequest $request, Product $product)
    {
        if ($request->user()->cannot('update', $product)){
            abort(403);
        }
        $product->update($request->validated());

        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $this->authorize('destroy', $product);

//        if (Auth::user()->cannot('destroy', $product)){
//            abort(403);
//        }
        $product->delete();

        return response()->json();
    }
}
