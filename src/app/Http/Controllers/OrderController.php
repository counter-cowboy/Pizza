<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\Collections\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $this->authorize('viewAdmin', Order::class);

        return new OrderCollection(Order::paginate(10));
    }

    public function store(OrderRequest $request, OrderService $service)
    {
        $this->authorize('create', Order::class);
        $data = $request->validated();

        $user_id = $request->user('api')->id;

        $orderToCreate=$service->store($user_id, $data);


        return new OrderResource($orderToCreate);
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        return new OrderResource($order);
    }


    public function update(OrderRequest $request, Order $order)
    {
        $this->authorize('update', $order);

        $order->update($request->validated());

        return new OrderResource($order);
    }

    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        $order->delete();

        return response()->json();
    }

    public function cancel(Order $order)
    {
        $this->authorize('create', Order::class);
        $order->status = 'canceled';
        $order->save();
    }
}
