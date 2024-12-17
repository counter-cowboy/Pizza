<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OrderService
{
    public array $arrayForResponse = [];

    public function store(int $user_id, array $data): JsonResponse|Order
    {
        $products = $data['products'];
        $totalPrice = 0;

        $orderToCreate = [
            'user_id' => $user_id,
            'status' => OrderStatusEnum::InProgress,
            'phone' => $data['phone'],
            'email' => $data['email'],
            'address' => $data['address'],
            'delivery_time' => $data['delivery_time']
        ];

        try {
            DB::beginTransaction();
            $order = Order::firstOrCreate($orderToCreate);

            $productsToReturn = [];

            foreach ($products as $product) {
                $quantity = $product['quantity'];
                $prod_id = $product['product_id'];


                $someProduct = Product::where('id', $prod_id)->first();
                $totalPrice += $someProduct->price * $quantity;

                $order->product()->attach($prod_id, ['quantity' => $quantity]);

                $productsToReturn[] = [
                    'id' => $prod_id,
                    'description' => $someProduct->description,
                    'image' => $someProduct->image,
                    'price' => $someProduct->price,
                    'quantity' => $quantity,
                    'category' => $someProduct->category->name
                ];
            }

            $order->total_amount = $totalPrice;
            $order->save();

            $orderToCreate[] = $totalPrice;
            $orderToCreate[] = $productsToReturn;

            $this->set($orderToCreate);

            DB::commit();

            return $order;

        } catch (\Exception $exception) {

            DB::rollBack();

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function set(array $data): void
    {
        $this->arrayForResponse = $data;
    }

    public function get(): array
    {
        return $this->arrayForResponse;
    }
}
