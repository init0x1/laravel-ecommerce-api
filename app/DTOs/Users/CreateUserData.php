<?php

namespace App\DTOs\Users;

use App\Entities\Enums\UserType;
use Illuminate\Http\Request;

class CreateUserData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public UserType $role,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            email: $request->input('email'),
            password: $request->input('password'),
            role: UserType::tryFrom($request->input('role')) ?? UserType::CUSTOMER,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'role' => $this->role->value,
        ];
    }
}
