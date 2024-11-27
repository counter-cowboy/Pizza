<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function addToCart($productId): JsonResponse
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            return response()->json('Already in cart', 409);
        } else {
            $cart[] = $productId;
            Session::put('cart', $cart);
            return response()->json(['Added to cart', 'cart' => $cart], 201);
        }
    }

    //TODO think about it
    public function viewCart(): JsonResponse
    {
        $userCart = null;
        $cart = Session::get('cart', []);
        if (Auth::check()) {
            $userCart = Cart::where('user_id', Auth::id())->get();
        }
        return response()->json($userCart, 201);
    }

    public static function createCartForAuth($cart, $userId): JsonResponse
    {
        if (!empty($cart)) {
            $newCart = Cart::create(['user_id' => $userId]);

            foreach ($cart as $productId => $item) {
                $newCart->product()->attach($productId);
            }
            Session::forget('cart');
            return response()->json($newCart, 201);
        } else {
            return response()->json(Cart::create(['user_id' => $userId]), 201);
        }
    }

}
