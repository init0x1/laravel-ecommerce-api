<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Orders\CreateOrderData;
use App\DTOs\Orders\UpdateOrderData;
use App\Entities\Enums\UserType;
use App\Entities\Models\Order;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\V1\CreateRequests\OrderCreateRequest;
use App\Http\Requests\Api\V1\UpdateRequests\OrderUpdateRequest;
use App\Services\OrderService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderController extends BaseApiController
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role === UserType::CUSTOMER) {

            $orders = $this->orderService->getUserOrders($user->id);
        } else {

            $orders = $this->orderService->getAllOrders();
        }

        return $this->successResponse($orders);
    }

    public function store(OrderCreateRequest $request): JsonResponse
    {
        try {
            $orderData = CreateOrderData::fromRequest($request);
            $order = $this->orderService->createOrder($orderData);

            return $this->createdResponse($order);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 422);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function show(int $id, Request $request): JsonResponse
    {
        try {
            $order = $this->orderService->getOrderById($id);
            $user = $request->user();

            if ($user->role === UserType::CUSTOMER && $order->customer_id !== $user->id) {
                return $this->errorResponse('Unauthorized: You can only view your own orders', 403);
            }

            return $this->successResponse($order);
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse('Order not found');
        }
    }

    public function update(OrderUpdateRequest $request, Order $order): JsonResponse
    {
        try {
            $user = $request->user();
            $updateData = UpdateOrderData::fromRequest($request);

            $updatedOrder = $this->orderService->updateOrder(
                order: $order,
                updateData: $updateData,
                userId: $user->id,
                userRole: $user->role
            );

            return $this->successResponse($updatedOrder);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 422);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 403);
        }
    }
}
