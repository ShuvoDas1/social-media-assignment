<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService)
    {

    }

    public function login(LoginRequest $request)
    {
        try {
            Log::channel('auth')->info("****** Login Data ******* ");
            Log::channel('auth')->info($request->validated());

            $loginData = $this->authService->login($request->validated());
            Log::channel('auth')->info("****** Login Success ******* ");

            return $this->successResponse('Login successful', 200, $loginData);
        } catch (\Throwable $th) {
            Log::channel('auth')->info("****** Login Error ******* ");
            Log::channel('auth')->info($th->getMessage());
            Log::channel('auth')->info($th->getTraceAsString());
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            Log::channel('auth')->info("****** Registration Data ******* ");
            Log::channel('auth')->info($request->validated());

            $registrationData = $this->authService->registration($request->validated());
            Log::channel('auth')->info("****** Registration Success ******* ");

            return $this->successResponse('Registration successful', 200, $registrationData);
        } catch (\Throwable $th) {
            Log::channel('auth')->info("****** Registration Error ******* ");
            Log::channel('auth')->info($th->getMessage());
            Log::channel('auth')->info($th->getTraceAsString());
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    public function logout()
    {
        try {
            $logoutData = $this->authService->logout();
            return $this->successResponse('Logout successful', 200, $logoutData);
        } catch (\Throwable $th) {
            Log::channel('auth')->info("******* Logout Error*********");
            Log::channel('auth')->info($th->getMessage());
            Log::channel('auth')->info($th->getTraceAsString());
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }
}
