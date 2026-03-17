<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use ApiResponser;

    public function __construct(private AuthService $authService) {}

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->authService->login($request->validated());

        return $this->success($user);
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return $this->success(['message' => 'Logged out successfully.']);
    }
}
