<?php

namespace App\Services;

use App\DTOs\Users\CreateUserData;
use App\DTOs\Users\LoginUserData;
use App\Permissions\V1\Abilities;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        $abilities = Abilities::getAbilities($user);
        $token = $user->createToken('auth_token', $abilities)->plainTextToken;

        return $this->prepareUserWithToken($user, $token, $abilities);
    }

    public function login(LoginUserData $credentials): ?array
    {
        if (! Auth::attempt($credentials->toArray())) {
            throw new AuthenticationException('Invalid credentials');
        }

        $user = Auth::user();

        $abilities = Abilities::getAbilities($user);
        $token = $user->createToken('auth_token', $abilities)->plainTextToken;

        return $this->prepareUserWithToken($user, $token, $abilities);
    }

    private function prepareUserWithToken($user, $token, $abilities): array
    {
        return [
            'user' => $user,
            'token' => $token,
            'abilities' => $abilities,
        ];
    }
}
