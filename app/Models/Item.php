<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'code',
        'name',
        'room',
        'floor',
        'status',            // ✅ Tambahkan ini
        'install_date',
        'replacement_date',
        'photo',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            // Jika belum ada kode, generate otomatis
            if (empty($item->code)) {
                $item->code = 'ITM-' . strtoupper(Str::random(8));
            }
            // Jika belum ada status, default active
            if (empty($item->status)) {
                $item->status = 'active';
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
