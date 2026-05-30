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
        $profil = $user->pencariKos;

        $profilePhotoUrl = $user->getFirstMediaUrl('foto_profil')
            ?: 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'User') . '&color=113C7A&background=EBF4FF';

        return response()->json([
            'success' => true,
            'user' => [
                'id'                => $user->id,
                'name'              => $user->name,
                'email'             => $user->email,
                'no_telepon'        => $user->no_telepon,
                'profile_photo_url' => $profilePhotoUrl,
                'jenis_kelamin'     => $profil?->jenis_kelamin,
                'pekerjaan'         => $profil?->pekerjaan,
                'kota_asal'         => $profil?->kota_asal,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name'          => 'sometimes|nullable|string|max:255',
            'no_telepon'    => 'sometimes|nullable|string|max:15',
            'jenis_kelamin' => 'sometimes|nullable|string',
            'pekerjaan'     => 'sometimes|nullable|string',
            'domisili'      => 'sometimes|nullable|string|max:255',
            'foto'          => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password_lama' => 'required_with:password_baru|string',
            'password_baru' => 'sometimes|required|string|min:8',
            'konfirmasi'    => 'required_with:password_baru|same:password_baru',
        ]);

        if ($request->has('name'))       $user->name       = $request->name;
        if ($request->has('no_telepon')) $user->no_telepon = $request->no_telepon;

        if ($request->filled('password_baru')) {
            if (!Hash::check($request->password_lama, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password lama tidak sesuai',
                ], 422);
            }
            $user->password = Hash::make($request->password_baru);
        }

        if ($request->hasFile('foto')) {
            $extension = $request->file('foto')->getClientOriginalExtension() ?: 'jpg';
            $user->clearMediaCollection('foto_profil');
            $user->addMedia($request->file('foto')->getRealPath())
                ->usingFileName('pencari-' . $user->id . '-' . now()->format('YmdHis') . '.' . $extension)
                ->toMediaCollection('foto_profil');
        }

        $user->save();

        $profil = $user->pencariKos()->firstOrCreate(['user_id' => $user->id]);

        if ($request->has('jenis_kelamin')) $profil->jenis_kelamin = $request->jenis_kelamin;
        if ($request->has('pekerjaan'))     $profil->pekerjaan     = $request->pekerjaan;
        if ($request->has('domisili'))      $profil->kota_asal     = $request->domisili;

        $profil->save();

        $profilePhotoUrl = $user->getFirstMediaUrl('foto_profil')
            ?: 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'User') . '&color=113C7A&background=EBF4FF';

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'user' => [
                'id'                => $user->id,
                'name'              => $user->name,
                'email'             => $user->email,
                'no_telepon'        => $user->no_telepon,
                'profile_photo_url' => $profilePhotoUrl,
                'jenis_kelamin'     => $profil->jenis_kelamin,
                'pekerjaan'         => $profil->pekerjaan,
                'kota_asal'         => $profil->kota_asal,
            ],
        ]);
    }
}