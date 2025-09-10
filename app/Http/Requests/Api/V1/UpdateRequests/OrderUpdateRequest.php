<?php

namespace App\Http\Requests\Api\V1\UpdateRequests;

use App\Entities\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled in controller/service
    }

    public function rules(): array
    {
        return [
            'shipping_address' => ['sometimes', 'string', 'max:500'],
            'status' => [
                'sometimes',
                'string',
                Rule::enum(OrderStatus::class),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_address.string' => 'Shipping address must be a string.',
            'shipping_address.max' => 'Shipping address cannot exceed 500 characters.',
            'status.string' => 'Status must be a string.',
            'status.enum' => 'Invalid order status. Valid statuses are: pending, processing, shipped, completed, cancelled.',
        ];
    }
}
