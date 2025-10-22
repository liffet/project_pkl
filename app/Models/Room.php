<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi (mass assignable)
    protected $fillable = [
        'name',
        'floor_id',
    ];

    /**
     * Relasi ke model Floor
     * Setiap Room berada di satu Floor
     */
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    /**
     * Relasi ke Item (jika 1 room punya banyak item)
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
