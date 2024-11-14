<?php

namespace App\Http\Controllers;

use App\Services\SupabaseAuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(SupabaseAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $result = $this->authService->registerUser($request->email, $request->password);
        if (isset($result['error']) && $result['error']) {
            return response()->json(['error' => $result['message']], 400);
        }

        return response()->json(['message' => 'User registered successfully!', 'data' => $result]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->authService->loginUser($request->email, $request->password);
        if (isset($result['error']) && $result['error']) {
            return response()->json(['error' => $result['message']], 400);
        }

        return response()->json(['message' => 'Login successful!', 'data' => $result]);
    }

    public function verify(Request $request)
    {
        $token = $request->query('access_token');

        $result = $this->authService->verifyEmail($token);
        // if (isset($result['error']) && $result['error']) {
        //     return response()->json(['error' => $result['message']], 400);
        // }

        return response()->json(['message' => 'Verified successful!', 'data' => $result]);
    }
}
