<?php

namespace App\DTOs\Stocks;

use App\Entities\Models\Product;

class CreateStockData
{
    public function __construct(
        public int $product_id,
        public int $quantity,
    ) {}

    public static function fromProduct(Product $product, int $quantity = 0): self
    {
        return new self(
            product_id: $product->id,
            quantity: $quantity,
        );
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
        ];
    }
}
