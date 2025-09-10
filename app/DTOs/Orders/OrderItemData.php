<?php

namespace App\DTOs\Orders;

class OrderItemData
{
    public function __construct(
        public int $order_id,
        public int $product_id,
        public int $quantity,
        public float $unit_price,
        public float $total_price,
    ) {}

    public static function fromProductData(int $order_id, array $productData, float $unit_price): self
    {
        $quantity = $productData['quantity'];
        $total_price = $quantity * $unit_price;

        return new self(
            order_id: $order_id,
            product_id: $productData['product_id'],
            quantity: $quantity,
            unit_price: $unit_price,
            total_price: $total_price,
        );
    }

    public function toArray(): array
    {
        return [
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'total_price' => $this->total_price,
        ];
    }
}
