<?php

namespace App\DTOs\Orders;

use App\Entities\Enums\OrderStatus;
use Illuminate\Http\Request;

class UpdateOrderData
{
    public function __construct(
        public ?string $shipping_address = null,
        public ?OrderStatus $status = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            shipping_address: $request->input('shipping_address'),
            status: $request->input('status') ? OrderStatus::from($request->input('status')) : null,
        );
    }

    public function toArray(): array
    {
        $data = [];

        if ($this->shipping_address !== null) {
            $data['shipping_address'] = $this->shipping_address;
        }

        if ($this->status !== null) {
            $data['status'] = $this->status;
        }

        return $data;
    }
}
