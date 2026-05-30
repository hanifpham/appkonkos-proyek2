<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'no_telepon' => 'required|string|min:12|max:15',
            'password'   => 'required|string|min:8',
        ]);

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'no_telepon'  => $request->no_telepon,
            'role'        => 'pencari',
            'status'      => 'aktif',
            'status_akun' => 1,
        ]);
        event(new Registered($user));

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil. Silakan cek email untuk verifikasi.',
            'token'   => $user->createToken('auth_token')->plainTextToken,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'email_verified' => false,
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password Salah',
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        if (!$user->status_akun) {
            return response()->json([
                'success' => false,
                'message' => 'Akun dinonaktifkan',
            ], 403);
        }

        // if (!$user->hasVerifiedEmail()) {
        //     return response()->json([
        //         'success'        => false,
        //         'message'        => 'Email belum diverifikasi. Silakan cek inbox Anda.',
        //         'email_verified' => false,
        //     ], 403);
        // }

        return response()->json([
            'success' => true,
            'token'   => $user->createToken('auth_token')->plainTextToken,
            'user'    => [
                'id'             => $user->id,
                'name'           => $user->name,
                'role'           => $user->role,
                'email_verified' => true,
            ],
        ]);
    }

    public function resendVerification(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Email tidak ditemukan'], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['success' => false, 'message' => 'Email sudah terverifikasi'], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['success' => true, 'message' => 'Email verifikasi telah dikirim ulang']);
    }
}