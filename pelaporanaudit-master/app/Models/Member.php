<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'member';

    // Kolom yang dapat diisi
    protected $fillable = [
        'user_id',
        'nama_toko',
        'alamat',
        'no_telp',
        'image',
    ];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
