<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Laravel\Jetstream\Jetstream;

class RegisterPemilikRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string|Password|Rule>>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', Rule::unique('users', 'email')],
            'no_telepon' => ['required', 'string', 'regex:/^[0-9]{10,15}$/'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'nama_bank' => ['required', 'string', 'max:100'],
            'nomor_rekening' => ['required', 'string', 'regex:/^[0-9]{8,30}$/'],
            'foto_ktp' => ['required', 'file', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];

        if (Jetstream::hasTermsAndPrivacyPolicyFeature()) {
            $rules['terms'] = ['accepted', 'required'];
        }

        return $rules;
    }
}
