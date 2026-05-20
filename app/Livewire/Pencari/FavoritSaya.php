<?php

namespace App\Livewire\Pencari;

use App\Models\Favorit;
use App\Models\Kontrakan;
use App\Models\Kosan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

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
        $favorits = Favorit::query()
            ->with([
                'favoritable' => function (MorphTo $morphTo): void {
                    $morphTo->morphWith([
                        Kosan::class => ['media', 'tipeKamar.kamar'],
                        Kontrakan::class => ['media'],
                    ]);
                },
            ])
            ->where('user_id', $user->id)
            ->whereHasMorph(
                'favoritable',
                [Kosan::class, Kontrakan::class],
                function (Builder $query, string $type): void {
                    if ($type === Kosan::class) {
                        $query->where('status', 'aktif')
                            ->whereHas('tipeKamar.kamar', fn (Builder $roomQuery): Builder => $roomQuery->where('status_kamar', 'tersedia'));
                    }

                    if ($type === Kontrakan::class) {
                        $query->where('status', 'aktif')
                            ->where('sisa_kamar', '>', 0);
                    }
                }
            )
            ->latest()
            ->paginate(6);

        return view('livewire.pencari.favorit-saya', [
            'user' => $user,
            'favorits' => $favorits,
        ]);
    }
}
