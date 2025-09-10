<?php

namespace App\Services;

use App\DTOs\Users\LoginUserData;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\DTOs\Users\CreateUserData;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function register(CreateUserData $userData): ?array
    {

        $userDataWithHashedPassword = new CreateUserData(
            name: $userData->name,
            email: $userData->email,
            password: Hash::make($userData->password),
            role: $userData->role
        );

        $user = $this->userRepository->create($userDataWithHashedPassword);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->prepareUserWithToken($user, $token);
    }

    public function login(LoginUserData $credentials): ?array
    {
        if (!Auth::attempt($credentials->toArray())) {
            throw new AuthenticationException('Invalid credentials');
        }

        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->prepareUserWithToken($user, $token);
    }

    private function prepareUserWithToken($user, $token): array
    {
        return [
            'user' => $user,
            'token' => $token,
        ];
    }

}
