<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamageReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device',
        'category_id',
        'reason',
        'status',
    ];

    // Relasi ke user (pelapor)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke kategori barang
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
