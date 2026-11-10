<?php

namespace App\Http\Controllers;

use App\Contract\Role;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    public function login(LoginRequest $request)
    {
        try {
            $response = $this->authService->login($request->validated(), [Role::$OWNER, Role::$CUSTOMER]);
            return response()->json($response, 200);
        }catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function adminLogin(LoginRequest $request)
    {
        try {
            $response = $this->authService->login($request->validated(), [Role::$ADMIN]);
            return response()->json($response, 200);
        }catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function register(RegisterRequest $request)
    {
        try{
            $response = $this->authService->register($request->validated());
            return response()->json($response, 200);
        }catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
        return response()->json(['message' => 'Signed out.']);
    }
}
