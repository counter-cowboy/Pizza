<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Http\Resources\CartCacheResource;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    public function __construct(protected CartService $service)
    {
    }

    public function index()
    {
        $this->authorize('isAdmin', Cart::class);

        return CartResource::collection(Cart::paginate(10));
    }

    public function store(CartRequest $request)
    {
        // For non-authenticated users
        if (!Auth::check()) {
            $this->service->addToCart($request->product_id);

            return response()->json(['message' => 'Items added'], Response::HTTP_CREATED);
        } else {
            // For authenticated, next step - check is_authorised
            $this->authorize('create', Cart::class);
            $data = $request->validated();

            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

            $cart->product()->attach($data['product_id']);

            return new CartResource($cart);
        }
    }

    public function show(Cart $cart)
    {
        $this->authorize('view', $cart);

        return new CartResource($cart);
    }

    public function update(CartRequest $request, Cart $cart)
    {
        $this->authorize('update', $cart);

        $cart->update($request->validated());

        return new CartResource($cart);
    }
}
