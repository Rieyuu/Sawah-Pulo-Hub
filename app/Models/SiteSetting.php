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
        $setting = self::where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    /**
     * Static helper untuk menyimpan/memperbarui setting
     */
    public static function setValue(string $key, ?string $value, string $type = 'text'): self
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'user_id' => auth()->check() ? auth()->id() : null,
            ]
        );
    }
}
