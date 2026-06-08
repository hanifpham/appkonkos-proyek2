<?php

namespace App\Livewire\Mitra;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class UbahPassword extends Component
{
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => __('Password saat ini yang Anda masukkan salah.'),
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($this->password),
        ])->save();

        $this->reset(['current_password', 'password', 'password_confirmation']);

        session()->flash('success', 'Password berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.mitra.ubah-password');
    }
}
