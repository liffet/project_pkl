<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi
    protected $fillable = [
        'name',
    ];

    /**
     * Relasi ke model Room
     * Satu Floor memiliki banyak Room
     */
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Relasi ke model Item
     * Satu Floor bisa memiliki banyak Item (langsung)
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
