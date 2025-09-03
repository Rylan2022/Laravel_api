<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; 
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->errors()], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'error' => false,
            'message' => 'User registered successfully',
            'data' => [
                'user'  => $user,
                'token' => $token
            ]
        ], 201);
    }

    // Login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => true, 'message' => 'Invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => true, 'message' => 'Could not create token'], 500);
        }

        return response()->json([
            'error' => false,
            'message' => 'Login successful',
            'data' => [
                'user'  => auth()->user(),
                'token' => $token
            ]
        ]);
        
    }

    // Get Authenticated User
    public function me()
    {
        return response()->json(auth()->user());
    }

    // Logout
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User logged out successfully']);
    }

    // Refresh Token
    public function refresh()
    {
        return response()->json([
            'token' => auth()->refresh()
        ]); 
    }
}
