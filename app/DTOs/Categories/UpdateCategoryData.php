<?php

namespace App\DTOs\Categories;

use Illuminate\Http\Request;

class UpdateCategoryData
{
    public function __construct(
        public int $id,
        public string $name,
        public string $description,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            id : $request->route('category'),
            name: $request->input('name'),
            description: $request->input('description'),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
