<?php

namespace App\Services;

use App\Models\Cart;
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
            return response()->json(['Added to cart', 'cart'=>$cart], 201);
        }

    }

    public function viewCart(): void
    {
        $cart = Session::get('cart', []);
        if (Auth::check()) {
            $userCart = Cart::where('user_id', Auth::user()->id)->get();
        }

    }

    public static function createCartForAuth($cart, $userId): void
    {
        if (!empty($cart)) {
            $newCart = Cart::create(['user_id' => $userId]);

            foreach ($cart as $productId => $item) {
                $newCart->product()->attach($productId);
            }
            Session::forget('cart');
        }

     Cart::create(['user_id' => $userId]);
    }

}
