<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'user' => [
                'id'                => $user->id,
                'name'              => $user->name,
                'email'             => $user->email,
                'no_telepon'        => $user->no_telepon,
                'profile_photo_url' => $user->profile_photo_url,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name'          => 'sometimes|required|string|max:255',
            'no_telepon'    => 'sometimes|nullable|string|max:15',
            'foto'          => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password_lama' => 'required_with:password_baru|string',
            'password_baru' => 'sometimes|required|string|min:8',
            'konfirmasi'    => 'required_with:password_baru|same:password_baru',
        ]);

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('no_telepon')) {
            $user->no_telepon = $request->no_telepon;
        }

        if ($request->filled('password_baru')) {
            if (!Hash::check($request->password_lama, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password lama tidak sesuai',
                ], 422);
            }
            $user->password = Hash::make($request->password_baru);
        }

        // Upload foto
        if ($request->hasFile('foto')) {
            $user->updateProfilePhoto($request->file('foto'));
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'user' => [
                'id'                => $user->id,
                'name'              => $user->name,
                'email'             => $user->email,
                'no_telepon'        => $user->no_telepon,
                'profile_photo_url' => $user->profile_photo_url,
            ],
        ]);
    }
}