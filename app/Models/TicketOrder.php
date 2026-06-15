<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'payment_bank_name',
        'payment_bank_account',
        'payment_bank_recipient',
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

    /**
     * Accessor untuk mendapatkan nama bank transfer
     */
    public function getPaymentBankNameAttribute()
    {
        return SiteSetting::getValue('payment_bank_name', 'Bank Mandiri');
    }

    /**
     * Accessor untuk mendapatkan nomor rekening bank transfer
     */
    public function getPaymentBankAccountAttribute()
    {
        return SiteSetting::getValue('payment_bank_account', '1420012345678');
    }

    /**
     * Accessor untuk mendapatkan nama penerima rekening bank transfer
     */
    public function getPaymentBankRecipientAttribute()
    {
        return SiteSetting::getValue('payment_bank_recipient', 'BUMDes Sawah Pulo');
    }
}
