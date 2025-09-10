<?php

namespace App\DTOs\Products;

use Illuminate\Http\Request;

class UpdateProductData
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public float $unit_price,
        public int $category_id,
    ) {}

    public static function fromRequest(Request $request): self
    {

        return new self(
            id: $request->route('product'),
            name: $request->input('name'),
            description: $request->input('description'),
            unit_price: $request->input('unit_price'),
            category_id: $request->input('category_id'),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'unit_price' => $this->unit_price,
            'category_id' => $this->category_id,
        ];
    }
}
