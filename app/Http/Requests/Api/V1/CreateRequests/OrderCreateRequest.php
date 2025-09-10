<?php

namespace App\Http\Requests\Api\V1\CreateRequests;

use Illuminate\Foundation\Http\FormRequest;

class OrderCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled in controller
    }

    public function rules(): array
    {
        return [
            'shipping_address' => ['required', 'string', 'max:500'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_address.required' => 'Shipping address is required.',
            'shipping_address.string' => 'Shipping address must be a string.',
            'shipping_address.max' => 'Shipping address cannot exceed 500 characters.',
            'products.required' => 'At least one product must be included in the order.',
            'products.array' => 'Products must be an array.',
            'products.min' => 'At least one product must be included in the order.',
            'products.*.product_id.required' => 'Product ID is required for each product.',
            'products.*.product_id.integer' => 'Product ID must be an integer.',
            'products.*.product_id.exists' => 'The selected product does not exist.',
            'products.*.quantity.required' => 'Quantity is required for each product.',
            'products.*.quantity.integer' => 'Quantity must be an integer.',
            'products.*.quantity.min' => 'Quantity must be at least 1.',
        ];
    }
}
