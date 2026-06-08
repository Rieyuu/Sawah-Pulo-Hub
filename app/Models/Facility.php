<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_path',
        'user_id',
    ];

    /**
     * Relasi ke User (admin pembuat/pengubah fasilitas)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
