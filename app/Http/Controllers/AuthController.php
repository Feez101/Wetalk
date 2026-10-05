<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:80', 'username' => 'required|alpha_dash|max:40|unique:users', 'email' => 'nullable|email|unique:users', 'password' => 'required|string|min:8|confirmed']);
        $user = User::create($data);
        Auth::login($user);
        $request->session()->regenerate();
        return response()->json($user, 201);
    }
    public function login(Request $request)
    {
        $credentials = $request->validate(['username' => 'required|string', 'password' => 'required|string']);
        abort_unless(Auth::attempt($credentials, true), 422, 'The supplied credentials are invalid.');
        $request->session()->regenerate();
        return $request->user();
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->noContent();
    }
}
