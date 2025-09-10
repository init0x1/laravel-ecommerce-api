<?php

namespace App\DTOs\Stocks;

use Illuminate\Http\Request;

class UpdateStockData
{
    public function __construct(
        public int $id,
        public int $quantity,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            id: $request->route('stock')->id,
            quantity: $request->input('quantity'),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
        ];
    }
}
