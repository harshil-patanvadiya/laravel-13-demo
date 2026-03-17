<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(array $inputs): array
    {
        $user = User::where('email', $inputs['email'])->first();

        if (! $user || ! Hash::check($inputs['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $data = [
            'message' => 'Login successful.',
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ];

        return $data;
    }

    public function logout(): void
    {
        auth('api')->user()->tokens()->delete();
    }
}
