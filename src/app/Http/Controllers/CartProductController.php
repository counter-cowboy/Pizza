<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartProductRequest;
use App\Http\Resources\CartProductResource;
use App\Models\CartProduct;

class CartProductController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', CartProduct::class);

        return CartProductResource::collection(CartProduct::all());
    }

    public function store(CartProductRequest $request)
    {
        $this->authorize('create', CartProduct::class);

        return new CartProductResource(CartProduct::create($request->validated()));
    }

    public function show(CartProduct $cartItem)
    {
        $this->authorize('view', $cartItem);

        return new CartProductResource($cartItem);
    }

    public function update(CartProductRequest $request, CartProduct $cartItem)
    {
        $this->authorize('update', $cartItem);

        $cartItem->update($request->validated());

        return new CartProductResource($cartItem);
    }

    public function destroy(CartProduct $cartItem)
    {
        $this->authorize('delete', $cartItem);

        $cartItem->delete();

        return response()->json();
    }
}
