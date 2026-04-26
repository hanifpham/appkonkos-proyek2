<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public const KEY_PLATFORM_COMMISSION = 'komisi_platform_persen';

    public const KEY_REFUND_DEDUCTION = 'potongan_refund_persen';

    protected $table = 'settings';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * @return array<string, string>
     */
    public static function defaults(): array
    {
        return [
            self::KEY_PLATFORM_COMMISSION => '5',
            self::KEY_REFUND_DEDUCTION => '25',
        ];
    }

    public static function getValue(string $key, string|int|float|null $default = null): string
    {
        $value = static::query()
            ->where('key', $key)
            ->value('value');

        if ($value === null || trim((string) $value) === '') {
            return (string) ($default ?? static::defaults()[$key] ?? '');
        }

        return (string) $value;
    }

    public static function getNumber(string $key, int|float $default = 0): float
    {
        $value = static::getValue($key, $default);

        return is_numeric($value) ? (float) $value : (float) $default;
    }

    public static function putValue(string $key, string|int|float $value): self
    {
        return static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => static::normalizeValue($value)]
        );
    }

    public static function ensureDefaults(): void
    {
        foreach (static::defaults() as $key => $value) {
            static::query()->firstOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }

    protected static function normalizeValue(string|int|float $value): string
    {
        if (is_int($value)) {
            return (string) $value;
        }

        if (is_float($value)) {
            return rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
        }

        if (is_numeric($value)) {
            return static::normalizeValue((float) $value);
        }

        return trim($value);
    }
}
