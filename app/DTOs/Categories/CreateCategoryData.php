<?php

namespace App\DTOs\Categories;

use Illuminate\Http\Request;

class CreateCategoryData
{
    public function __construct(
        public string $name,
        public string $description,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            description: $request->input('description'),
        );
    }


    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
        ];
    }

}
