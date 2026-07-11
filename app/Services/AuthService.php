<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthService
{
    public function __construct(protected UserService $userService)
    {

    }

    public function login(array $credentials): array
    {
        try {
            $user = $this->userService->getUserByField('email', $credentials['email']);

            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                throw new Exception('Invalid credentials', 401);
            }

            $user->last_login_at = now();
            $user->save();

            $token = Auth::guard('api')->login($user);
            if ($token) {
                return [
                    'user' => $user,
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
                ];
            }

            throw new Exception('Failed to generate token', 500);
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function registration(array $credentials): array
    {
        try {
            $user = $this->userService->getUserByField('email', $credentials['email']);

            if ($user) {
                throw new Exception('User already exists', 409);
            }

            $user = User::create([
                'fname' => $credentials['fname'],
                'lname' => $credentials['lname'],
                'email' => $credentials['email'],
                'password' => Hash::make($credentials['password']),
            ]);

            $token = Auth::guard('api')->login($user);

            return [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function logout(): bool
    {
        try {
            $user = Auth::guard('api')->user();
            $user->last_logout_at = now();
            $user->save();
            Auth::guard('api')->logout();
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
