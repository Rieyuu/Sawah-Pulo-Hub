<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'user_id',
    ];

    protected static $cachedSettings = null;

    /**
     * Relasi ke User (admin pengubah setting)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Static helper untuk mengambil nilai setting secara langsung
     */
    public static function getValue(string $key, $default = null): ?string
    {
        if (self::$cachedSettings === null) {
            self::$cachedSettings = self::pluck('value', 'key')->toArray();
        }

        return array_key_exists($key, self::$cachedSettings) ? self::$cachedSettings[$key] : $default;
    }

    /**
     * Static helper untuk menyimpan/memperbarui setting
     */
    public static function setValue(string $key, ?string $value, string $type = 'text'): self
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'user_id' => auth()->check() ? auth()->id() : null,
            ]
        );

        // Reset cache so subsequent calls reflect the new value
        self::$cachedSettings = null;

        return $setting;
    }
}
