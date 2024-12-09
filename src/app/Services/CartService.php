<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CartService
{
    public function addToCart($productId): JsonResponse
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            return response()->json('Already in cart', Response::HTTP_CONFLICT);
        } else {
            $cart[] = $productId;
            Session::put('cart', $cart);
            return response()->json(['Added to cart', 'cart' => $cart], Response::HTTP_OK);
        }
    }

    //TODO think about it
    public function viewCart(): JsonResponse
    {
        $userCart = null;

        if (Auth::check()) {
            $userCart = Cart::where('user_id', Auth::id())->get();
            return response()->json($userCart, Response::HTTP_OK);
        }

        return Session::get('cart', []);
    }

    public static function createCartForAuth($cart, $userId): JsonResponse
    {
        if (!empty($cart)) {
            $newCart = Cart::create(['user_id' => $userId]);

            foreach ($cart as $productId => $item) {
                $newCart->product()->attach($productId);
            }
            Session::forget('cart');
            return response()->json($newCart, Response::HTTP_CREATED);
        } else {
            return response()->json(Cart::create(['user_id' => $userId]), Response::HTTP_CREATED);
        }
    }

}
