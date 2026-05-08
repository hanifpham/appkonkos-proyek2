<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Models\PemilikProperti;
use App\Models\PencariKos;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, mixed>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'no_telepon' => ['required', 'string', 'regex:/^[0-9]{10,15}$/'],
            'password' => $this->passwordRules(),
            // Hanya izinkan 'pencari' dan 'pemilik'. JANGAN pernah izinkan 'superadmin'.
            'role' => ['required', 'string', Rule::in(['pencari', 'pemilik'])],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        // Sanitasi ketat: paksa role hanya pencari/pemilik
        $role = in_array($input['role'], ['pencari', 'pemilik'], true)
            ? $input['role']
            : 'pencari';

        return DB::transaction(function () use ($input, $role): User {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'no_telepon' => $input['no_telepon'],
                'role' => $role,
                'status_akun' => true,
                'password' => Hash::make($input['password']),
            ]);

            // Buat profil terkait sesuai role
            if ($role === 'pencari') {
                PencariKos::create(['user_id' => $user->id]);
            } elseif ($role === 'pemilik') {
                PemilikProperti::create(['user_id' => $user->id]);
            }

            return $user;
        });
    }
}
