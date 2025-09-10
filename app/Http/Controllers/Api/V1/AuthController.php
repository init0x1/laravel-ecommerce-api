<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Users\CreateUserData;
use App\DTOs\Users\LoginUserData;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\V1\Auth\StoreUserRequest;
use App\Http\Requests\Api\V1\Auth\LoginUserRequest;
use App\Services\AuthService;
use Illuminate\Auth\AuthenticationException;


class AuthController extends BaseApiController
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(StoreUserRequest $request)
    {
        $user = $this->authService->register(CreateUserData::fromRequest($request));

        return $this->createdResponse($user);
    }

    public function login(LoginUserRequest $request)
    {
        try {
            $result = $this->authService->login(LoginUserData::fromRequest($request));
            return $this->successResponse($result);
        } catch (AuthenticationException $e) {
            return $this->errorResponse($e->getMessage(), 401);
        }
    }
}
