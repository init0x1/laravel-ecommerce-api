<?php

namespace App\Services;

use App\DTOs\Orders\CreateOrderData;
use App\DTOs\Orders\OrderItemData;
use App\DTOs\Orders\UpdateOrderData;
use App\Entities\Enums\OrderStatus;
use App\Entities\Enums\UserType;
use App\Entities\Models\Order;
use App\Repositories\Contracts\OrderItemRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\StockRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Policies\V1\OrderPolicy;

class OrderService extends BaseService
{

     protected $policyClass = OrderPolicy::class;
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected OrderItemRepositoryInterface $orderItemRepository,
        protected ProductRepositoryInterface $productRepository,
        protected StockRepositoryInterface $stockRepository,
    ) {}

    public function createOrder(CreateOrderData $orderData): Order
    {
        if (! $this->isAble('store', Order::class)) {
            throw new AuthorizationException('You do not have permission to create an order.');
        }

        return DB::transaction(function () use ($orderData) {

            // validate products
            $validatedProducts = $this->validateAndCalculateProducts($orderData->products);

            // create the order
            $orderData = new CreateOrderData(
                customer_id: $orderData->customer_id,
                shipping_address: $orderData->shipping_address,
                products: $orderData->products,
            );

            $order = $this->orderRepository->create($orderData);

            // total amount and create order items
            $totalAmount = 0;
            $orderItemsData = [];

            foreach ($validatedProducts as $productData) {
                $product = $productData['product'];
                $quantity = $productData['quantity'];
                $unitPrice = $product->unit_price;

                $orderItemData = OrderItemData::fromProductData(
                    order_id: $order->id,
                    productData: [
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                    ],
                    unit_price: $unitPrice
                );

                $orderItemsData[] = $orderItemData;
                $totalAmount += $orderItemData->total_price;

                // reduce stock quantity
                $this->reduceStockQuantity($product->id, $quantity);
            }

            // create order items
            $this->orderItemRepository->createMany($orderItemsData);

            // update order total amount
            $order->update(['total_amount' => $totalAmount]);

            return $order->fresh(['orderItems.product', 'customer']);
        });
    }

    public function getAllOrders(): LengthAwarePaginator
    {
        return $this->orderRepository
            ->applyQuery()
            ->paginate();
    }

    public function getOrderById(int $id): ?Order
    {
        $order = $this->orderRepository->find($id);

        if (! $order) {
            throw new ModelNotFoundException('Order not found');
        }

        $order->load(['orderItems.product', 'customer']);

        return $order;
    }

    public function getUserOrders(int $userId): LengthAwarePaginator
    {
        return $this->orderRepository
            ->applyQuery()
            ->where('customer_id', $userId)
            ->paginate();
    }

    public function updateOrder(Order $order, UpdateOrderData $updateData, int $userId, UserType $userRole): Order
    {
        if(!$this->isAble('update', Order::class)){
            throw new AuthorizationException('You do not have permission to update this order data');
        }
        // incase of cancelling order, restore stock quantities
        if ($updateData->status === OrderStatus::CANCELLED && $order->status !== OrderStatus::CANCELLED) {
            $this->restoreStockQuantities($order);
        }

        return $this->orderRepository->update($order, $updateData);
    }

    protected function validateAndCalculateProducts(array $products): array
    {
        $validatedProducts = [];

        foreach ($products as $productData) {
            if (! isset($productData['product_id']) || ! isset($productData['quantity'])) {
                throw ValidationException::withMessages([
                    'products' => 'Each product must have product_id and quantity',
                ]);
            }

            $product = $this->productRepository->find($productData['product_id']);
            if (! $product) {
                throw ValidationException::withMessages([
                    'products' => "Product with ID {$productData['product_id']} not found",
                ]);
            }

            $quantity = (int) $productData['quantity'];
            if ($quantity <= 0) {
                throw ValidationException::withMessages([
                    'products' => 'Quantity must be greater than 0',
                ]);
            }

            // check stock availability
            $stock = $product->stock;
            if (! $stock || $stock->quantity < $quantity) {
                $availableStock = $stock ? $stock->quantity : 0;
                throw ValidationException::withMessages([
                    'products' => "Insufficient stock for product: {$product->name}. Available: {$availableStock}, Requested: {$quantity}",
                ]);
            }

            $validatedProducts[] = [
                'product' => $product,
                'quantity' => $quantity,
            ];
        }

        return $validatedProducts;
    }

    protected function reduceStockQuantity(int $productId, int $quantity): void
    {
        $stock = $this->stockRepository->where('product_id', $productId)->first();
        if ($stock) {
            $stock->decrement('quantity', $quantity);
        }
    }

    protected function restoreStockQuantities(Order $order): void
    {
        foreach ($order->orderItems as $orderItem) {
            $stock = $this->stockRepository->where('product_id', $orderItem->product_id)->first();
            if ($stock) {
                $stock->increment('quantity', $orderItem->quantity);
            }
        }
    }
}
