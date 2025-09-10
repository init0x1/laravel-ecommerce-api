<?php

namespace App\DTOs\Users;


use Illuminate\Http\Request;

class LoginUserData
{
    public function __construct(
        public string $email,
        public string $password,

    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            email: $request->input('email'),
            password: $request->input('password'),
        );
    }


    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }

}
