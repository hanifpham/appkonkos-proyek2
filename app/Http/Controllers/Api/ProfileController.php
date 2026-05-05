<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user()->load('pencariKos');
        $pencari = $user->pencariKos;

        return response()->json([
            'success' => true,
            'user' => [
                'id'                => $user->id,
                'name'              => $user->name,
                'email'             => $user->email,
                'no_telepon'        => $user->no_telepon,
                'profile_photo_url' => $user->profile_photo_url,
                'no_wa'             => $pencari?->no_wa ?? null,
                'jenis_kelamin'     => $pencari?->jenis_kelamin ?? null,
                'pekerjaan'         => $pencari?->pekerjaan ?? null,
                'domisili'          => $pencari?->domisili ?? null,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user()->load('pencariKos');

        $request->validate([
            'name'          => 'sometimes|required|string|max:255',
            'no_telepon'    => 'sometimes|nullable|string|max:15',
            'no_wa'         => 'sometimes|nullable|string|max:15',
            'jenis_kelamin' => 'sometimes|nullable|in:Laki-laki,Perempuan',
            'pekerjaan'     => 'sometimes|nullable|in:Mahasiswa,Karyawan,Lainnya',
            'domisili'      => 'sometimes|nullable|string|max:255',
            'foto'          => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password_lama' => 'required_with:password_baru|string',
            'password_baru' => 'sometimes|required|string|min:8',
            'konfirmasi'    => 'required_with:password_baru|same:password_baru',
        ]);

        if ($request->has('name')) $user->name = $request->name;
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
            $user->updateProfilePhoto($request->file('foto'));
        }

        $user->save();
        $pencari = $user->pencariKos;
        if ($pencari) {
            if ($request->has('no_wa')) $pencari->no_wa = $request->no_wa;
            if ($request->has('jenis_kelamin')) $pencari->jenis_kelamin = $request->jenis_kelamin;
            if ($request->has('pekerjaan')) $pencari->pekerjaan = $request->pekerjaan;
            if ($request->has('domisili')) $pencari->domisili = $request->domisili;
            $pencari->save();
        }

        $user->refresh()->load('pencariKos');

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'user' => [
                'id'                => $user->id,
                'name'              => $user->name,
                'email'             => $user->email,
                'no_telepon'        => $user->no_telepon,
                'profile_photo_url' => $user->profile_photo_url,
                'no_wa'             => $user->pencariKos?->no_wa ?? null,
                'jenis_kelamin'     => $user->pencariKos?->jenis_kelamin ?? null,
                'pekerjaan'         => $user->pencariKos?->pekerjaan ?? null,
                'domisili'          => $user->pencariKos?->domisili ?? null,
            ],
        ]);
    }

    public function status(Request $request)
{
    $user = $request->user()->load('pencariKos');
    $pencari = $user->pencariKos;

    $isComplete =
        !empty($user->name) &&
        !empty($user->email) &&
        !empty($user->no_telepon) &&
        $pencari &&
        !empty($pencari->no_wa) &&
        !empty($pencari->jenis_kelamin) &&
        !empty($pencari->pekerjaan) &&
        !empty($pencari->domisili);

    return response()->json([
        'success' => true,
        'is_complete' => $isComplete,
        'missing_fields' => [
            'name' => empty($user->name),
            'email' => empty($user->email),
            'no_telepon' => empty($user->no_telepon),
            'no_wa' => empty($pencari?->no_wa),
            'jenis_kelamin' => empty($pencari?->jenis_kelamin),
            'pekerjaan' => empty($pencari?->pekerjaan),
            'domisili' => empty($pencari?->domisili),
        ],
    ]);
}
}