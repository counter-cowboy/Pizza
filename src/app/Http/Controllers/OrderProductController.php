<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderProductRequest;
use App\Http\Resources\OrderProductResource;
use App\Models\OrderProduct;

class OrderProductController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', OrderProduct::class);

        return OrderProductResource::collection(OrderProduct::all());
    }

    public function store(OrderProductRequest $request)
    {
        $this->authorize('create', OrderProduct::class);

        return new OrderProductResource(OrderProduct::create($request->validated()));
    }

    public function show(OrderProduct $orderItem)
    {
        $this->authorize('view', $orderItem);

        return new OrderProductResource($orderItem);
    }

    public function update(OrderProductRequest $request, OrderProduct $orderItem)
    {
        $this->authorize('update', $orderItem);

        $orderItem->update($request->validated());

        return new OrderProductResource($orderItem);
    }

    public function destroy(OrderProduct $orderItem)
    {
        $this->authorize('delete', $orderItem);

        $orderItem->delete();

        return response()->json();
    }
}
