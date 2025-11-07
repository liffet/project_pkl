<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamageReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'reason',
        'photo',
        'status',
    ];

    /**
     * 🔹 Relasi ke User (pelapor)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 🔹 Relasi ke Item yang dilaporkan rusak
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    /**
     * 🔹 Helper untuk menampilkan label status
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'accepted' => 'Diterima',
            'rejected' => 'Ditolak',
            default => 'Pending',
        };
    }
}
