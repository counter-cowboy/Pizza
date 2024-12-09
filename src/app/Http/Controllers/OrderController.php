<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\Collections\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\OrderValidationCountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function index(Request $request): OrderCollection
    {
        if (Auth::user()->is_admin===1) {
            return new OrderCollection(Order::paginate(20));
        } else {
            $user = $request->user();

            return new OrderCollection($user->order);
        }
    }

    public function store(
        OrderRequest                $request,
        OrderService                $service,
        OrderValidationCountService $orderValidation
    ): OrderResource|JsonResponse
    {
        $this->authorize('create', Order::class);

        $user_id = $request->user()->id;

        $data = $request->validated();
        $errors = $orderValidation->validateProductCount($data['products']);

        $jsonErrors = [];

        if (!empty($errors)) {

            foreach ($errors as $error => $value) {
                $jsonErrors[] = $value;
            }
            return response()->json($jsonErrors, Response::HTTP_UNPROCESSABLE_ENTITY);

        } else {
            $order = $service->store($user_id, $data);


            return new OrderResource($order);
        }
    }

    public function show(Order $order): OrderResource
    {
        $this->authorize('view', $order);

        return new OrderResource($order);
    }


    public function update(OrderRequest $request, Order $order): OrderResource
    {
        $this->authorize('update', $order);

        $order->update($request->validated());

        return new OrderResource($order);
    }


    public function cancel(Order $order): JsonResponse
    {
        $this->authorize('update', Order::class);

        $order->status = 'canceled';
        $order->save();
        return response()->json();
    }
}
