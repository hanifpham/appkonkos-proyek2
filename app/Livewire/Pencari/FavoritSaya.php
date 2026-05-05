<?php

namespace App\Livewire\Pencari;

use App\Models\Favorit;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class FavoritSaya extends Component
{
    use WithPagination;

    /**
     * Menghapus properti dari daftar favorit.
     */
    public function hapusFavorit($id)
    {
        $favorit = Favorit::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($favorit) {
            $favorit->delete();
            session()->flash('success', 'Properti berhasil dihapus dari daftar favorit.');
        } else {
            session()->flash('error', 'Data tidak ditemukan.');
        }
    }

    #[Layout('layouts.public')]
    public function render()
    {
        $user = Auth::user();
        
        // Mengambil data favorit milik user yang login dengan Eager Loading polymorphic
        // Kita load 'media' agar thumbnail bisa tampil tanpa query tambahan
        $favorits = Favorit::with(['favoritable.media'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(6);

        return view('livewire.pencari.favorit-saya', [
            'user' => $user,
            'favorits' => $favorits,
        ]);
    }
}
