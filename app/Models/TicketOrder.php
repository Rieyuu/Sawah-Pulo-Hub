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
