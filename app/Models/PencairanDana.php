<?php

declare(strict_types=1);

namespace App\Models;

use App\Notifications\AdminAlert;
use App\Support\Notifications\SuperAdminNotifier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PencairanDana extends Model
{
    use HasFactory;

    protected $table = 'pencairan_dana';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'pemilik_properti_id',
        'nominal',
        'status',
        'catatan_admin',
    ];

    protected static function booted(): void
    {
        static::created(function (PencairanDana $pencairanDana): void {
            if (! in_array($pencairanDana->status, ['pending', 'menunggu'], true)) {
                return;
            }

            $pencairanDana->loadMissing('pemilikProperti.user');

            $ownerName = $pencairanDana->pemilikProperti?->user?->name ?? 'Mitra';

            SuperAdminNotifier::send(new AdminAlert(
                title: 'Permintaan Pencairan Dana',
                message: sprintf('%s mengajukan pencairan dana sebesar Rp %s.', $ownerName, number_format((int) $pencairanDana->nominal, 0, ',', '.')),
                icon: 'account_balance_wallet',
                actionUrl: route('superadmin.pencairan'),
                actionLabel: 'Tinjau Pencairan',
                color: 'green',
            ));
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'nominal' => 'integer',
        ];
    }

    public function pemilikProperti(): BelongsTo
    {
        return $this->belongsTo(PemilikProperti::class, 'pemilik_properti_id');
    }

    public function getAlasanPenolakanAttribute(): ?string
    {
        return $this->catatan_admin;
    }
}
