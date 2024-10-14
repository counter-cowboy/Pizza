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
            return response()->json('Already in cart',);
        } else {
            $cart[] = $productId;
            Session::put('cart', $cart);
            return response()->json('Added to cart');
        }

    }

    public function viewCart(): void
    {
        $cart = Session::get('cart', []);
        if (Auth::check()) {
            $userCart = Cart::where('user_id', Auth::user()->id)->get();
        }

    }

    public static function createCartForAuth($cart)
    {
        if (!empty($cart)) {
            $user = Auth::user();
            $newCart = Cart::create(['user_id' => $user->id]);

            foreach ($cart as $productId => $item) {
                $newCart->product->id = $productId;
            }
        }

        Session::forget('cart');
    }

}
