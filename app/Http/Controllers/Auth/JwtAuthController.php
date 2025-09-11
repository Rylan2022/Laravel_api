<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtAuthController extends Controller
{

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = JWTAuth::fromUser($user);
        session(['jwt_token' => $token]);

        return redirect('/dashboard');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credential = $request->only('email', 'password');

        if (!Auth::attempt($credential)) {
            return back()->with('error', 'Invalid credentials');
        }

        $user = Auth::user();
        $token = JWTAuth::fromUser($user);
        session(['jwt_token' => $token]);

        return redirect('/dashboard');
    }

    public function dashboard()
    {
        $token = session('jwt_token');
        if (!$token) {
            return redirect('/login');
        }

        try {
            $user = JWTauth::setToken($token)->authenticate();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Session expired, login again');
        }

        return view('dashboard', compact('user'));
    }

    public function logout(Request $request)
    {
        $token = session('jwt_token');
        if ($token) {
            try {
                JWTAuth::setToken($token)->invalidate();
            } catch (\Exception $e) {
            }
        }

        session()->forget('jwt_token');

        return redirect('/login')->with('success', 'Logged out successfully');
    }
}
