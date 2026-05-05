<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Favorit extends Model
{
    use HasFactory;

    protected $table = 'favorits';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'favoritable_type',
        'favoritable_id',
    ];

    /**
     * Pemilik favorit ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Properti yang difavoritkan (Kosan atau Kontrakan).
     */
    public function favoritable(): MorphTo
    {
        return $this->morphTo();
    }
}
