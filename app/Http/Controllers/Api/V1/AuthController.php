<?php

namespace App\Http\Controllers\Api\V1;

use App\Entities\Models\User;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\V1\Auth\StoreUserRequest;
use App\Http\Requests\Api\V1\Auth\LoginUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends BaseApiController
{
    public function register(StoreUserRequest $request)
    {
        $newUserData = $request->validated();

        $newUser = User::create([
            'name' => $newUserData['name'],
            'email' => $newUserData['email'],
            'password' => Hash::make($newUserData['password']),
            'role' => $newUserData['role'],
        ]);

        $token = $newUser->createToken('auth_token')->plainTextToken;

        return $this->jsonResponse(
            [
                'user' => $newUser,
                'token' => $token,
            ],
            'User registered successfully.',
            true,
            201
        );
    }


    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return $this->jsonResponse(
                null,
                'Invalid credentials',
                false,
                401
            );
        }

        $user = User::firstWhere('email', $request->email);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->jsonResponse(
            [
                'user' => $user,
                'token' => $token,
            ],
            'User logged in successfully.',
            true,
            200
        );
    }
}
