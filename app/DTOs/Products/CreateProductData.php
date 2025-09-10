<?php

namespace App\DTOs\Products;

use Illuminate\Http\Request;


class CreateProductData
{
    public function __construct(
        public string $name,
        public ?string $description,
        public float $unit_price,
        public int $category_id,
        public int $seller_id,
        public int $initial_quantity,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            description: $request->input('description'),
            unit_price: $request->input('unit_price'),
            category_id: $request->input('category_id'),
            seller_id: $request->user()->id,
            initial_quantity: $request->input('initial_quantity', 0),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'unit_price' => $this->unit_price,
            'category_id' => $this->category_id,
            'seller_id' => $this->seller_id,
        ];
    }
}
