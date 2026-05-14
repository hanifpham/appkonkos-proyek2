<?php

namespace App\Livewire\Pencari;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilSaya extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $no_wa = '';
    public string $jenis_kelamin = '';
    public string $pekerjaan = '';
    public string $domisili = '';
    public ?string $foto_profil = null;
    public mixed $new_foto_profil = null;

    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    public function mount()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $pencari = $user->pencariKos;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->no_wa = $user->no_telepon ?? '';
            
            if ($pencari) {
                if ($pencari->jenis_kelamin === 'L') {
                    $this->jenis_kelamin = 'Laki-laki';
                } elseif ($pencari->jenis_kelamin === 'P') {
                    $this->jenis_kelamin = 'Perempuan';
                } else {
                    $this->jenis_kelamin = $pencari->jenis_kelamin ?? '';
                }
                
                $this->pekerjaan = $pencari->pekerjaan ?? '';
                $this->domisili = $pencari->kota_asal ?? '';
            }
            $this->foto_profil = $user->profile_photo_path ?? null;
        }
    }

    public function updateProfile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $this->validate([
            'name' => 'required|string|max:255',
            'no_wa' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'pekerjaan' => 'nullable|in:Mahasiswa,Karyawan,Lainnya',
            'domisili' => 'nullable|string|max:255',
            'new_foto_profil' => 'nullable|image|max:2048', // max 2MB
        ]);

        $userData = [
            'name' => $this->name,
            'no_telepon' => $this->no_wa,
        ];

        if ($this->new_foto_profil) {
            // Delete old photo if exists
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            // Store new photo
            $path = $this->new_foto_profil->store('profile-photos', 'public');
            $userData['profile_photo_path'] = $path;
            $this->foto_profil = $path;
        }

        $user->forceFill($userData)->save();

        // Update PencariKos relation
        $pencari = $user->pencariKos;
        if (!$pencari) {
            $pencari = new \App\Models\PencariKos();
            $pencari->user_id = $user->id;
        }

        $jk = null;
        if ($this->jenis_kelamin === 'Laki-laki') {
            $jk = 'L';
        } elseif ($this->jenis_kelamin === 'Perempuan') {
            $jk = 'P';
        }

        $pencari->jenis_kelamin = $jk;
        $pencari->pekerjaan = $this->pekerjaan ?: null;
        $pencari->kota_asal = $this->domisili ?: null;
        $pencari->save();

        // Reset the file input
        $this->new_foto_profil = null;

        session()->flash('success_profile', 'Profil berhasil diperbarui!');
        $this->dispatch('profile-updated'); // Optional: for browser events like toast/sweetalert
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Kata sandi saat ini tidak cocok.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        session()->flash('success_password', 'Kata sandi berhasil diperbarui!');
        $this->dispatch('password-updated');
    }

    #[Layout('layouts.public')]
    public function render()
    {
        return view('livewire.pencari.profil-saya');
    }
}
