<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SiteSetting;

class TicketOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ticket_id',
        'quantity',
        'total_price',
        'ticket_code',
        'proof_of_payment',
        'status',
        'is_used',
        'used_at',
        'expired_at',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'is_used' => 'boolean',
        'used_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    protected $appends = [
        'payment_deadline',
        'payment_qris_image',
    ];

    /**
     * Accessor untuk mendapatkan batas waktu pembayaran
     */
    public function getPaymentDeadlineAttribute()
    {
        $timeoutHours = (int) SiteSetting::getValue('payment_timeout_hours', 2);
        return $this->created_at ? $this->created_at->addHours($timeoutHours)->toIso8601String() : null;
    }

    /**
     * Accessor untuk mendapatkan gambar QRIS dinamis
     */
    public function getPaymentQrisImageAttribute()
    {
        return SiteSetting::getValue('payment_qris_image', '/images/qris_placeholder.png');
    }

    /**
     * Memeriksa dan membatalkan pesanan pending_payment yang melewati tenggat waktu
     */
    public static function checkAndCancelExpired($userId = null)
    {
        $timeoutHours = (int) SiteSetting::getValue('payment_timeout_hours', 2);

        $query = self::where('status', 'pending_payment')
            ->where('created_at', '<', now()->subHours($timeoutHours));

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $query->update(['status' => 'failed']);
    }

    /**
     * Relasi ke User (pembeli)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Ticket (tiket yang dipesan)
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
