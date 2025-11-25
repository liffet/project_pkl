<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'category_id',
        'room_id',
        'building_id',      // Ubah dari 'room' menjadi 'room_id'
        'floor_id',     // Ubah dari 'floor' menjadi 'floor_id'
        'status',
        'install_date',
        'replacement_date',
        'photo',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            // Generate kode otomatis jika belum ada
            if (empty($item->code)) {
                $item->code = 'ITM-' . strtoupper(Str::random(8));
            }

            // Default status "active" jika belum diisi
            if (empty($item->status)) {
                $item->status = 'active';
            }
        });
    }

    // Relasi ke kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke ruangan
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Relasi ke lantai
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }
    public function building()
{
    return $this->belongsTo(Building::class);
}
}
