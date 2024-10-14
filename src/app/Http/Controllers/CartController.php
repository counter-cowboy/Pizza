<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    private $service;

    public function __construct(CartService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $this->authorize('isAdmin', Cart::class);

        return CartResource::collection(Cart::paginate(10));
    }

    public function store(CartRequest $request)
    {
        // For non-authenticated users
        if (!Auth::user()) {
            $this->service->addToCart($request->validated()['product_id']);
        }

        // For authenticated, next step - check is_authorised
        $this->authorize('create', Cart::class);
        $data = $request->validated();

        $cart = Cart::firstOrCreate(['user_id' => $data['user_id']]);

        $cart->product()->attach($data['product_id']);

        return new CartResource($cart);
    }

    public function show(Cart $cart)
    {
        $this->authorize('view', $cart);
        $cart = Cart::findOrFail($cart)->with('product');

        return new CartResource($cart);
    }

    public function update(CartRequest $request, Cart $cart)
    {
        $this->authorize('update', $cart);

        $cart->update($request->validated());

        return new CartResource($cart);
    }

    public function destroy(Cart $cart)
    {
        $this->authorize('delete', $cart);

        $cart->delete();

        return response()->json();
    }


}
