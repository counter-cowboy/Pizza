<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartItemsRequest;
use App\Http\Resources\CartItemsResource;
use App\Models\CartItems;

class CartItemsController extends Controller
{
    public function index()
    {
        return CartItemsResource::collection(CartItems::all());
    }

    public function store(CartItemsRequest $request)
    {
        return new CartItemsResource(CartItems::create($request->validated()));
    }

    public function show(CartItems $cartItems)
    {
        return new CartItemsResource($cartItems);
    }

    public function update(CartItemsRequest $request, CartItems $cartItems)
    {
        $cartItems->update($request->validated());

        return new CartItemsResource($cartItems);
    }

    public function destroy(CartItems $cartItems)
    {
        $cartItems->delete();

        return response()->json();
    }
}
