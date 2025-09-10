<?php

namespace App\DTOs\Orders;

use Illuminate\Http\Request;

class CreateOrderData
{
    public function __construct(
        public int $customer_id,
        public string $shipping_address,
        public array $products,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            customer_id: $request->user()->id,
            shipping_address: $request->input('shipping_address'),
            products: $request->input('products', []),
        );
    }

    public function toArray(): array
    {
        return [
            'customer_id' => $this->customer_id,
            'shipping_address' => $this->shipping_address,
            'status' => 'pending',
            'total_amount' => 0.00,
        ];
    }
}
