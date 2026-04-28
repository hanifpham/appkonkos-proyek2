<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telepon' => '-', 
            'role' => 'pencari',
            'status' => 'aktif',
            'status_akun' => 1,
        ]);
        
        return response()->json([
            'success' => true,
            'token' => $user->createToken('auth_token')->plainTextToken, // DIPERBAIKI: Hapus spasi di 'token'
            'user' => $user,
        ], 201);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['success' => false, 'message' => 'Email/Password Salah'], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        
        // Cek status blokir sesuai Dashboard Super Admin
        if (!$user->status_akun) {
            return response()->json(['success' => false, 'message' => 'Akun dinonaktifkan'], 403);
        }

        return response()->json([
            'success' => true,
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role
            ]
        ]);
    }
}