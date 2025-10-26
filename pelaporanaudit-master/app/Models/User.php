<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Nama tabel (opsional jika menggunakan konvensi Laravel)
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone', // Kolom tambahan
        'user_type', // Opsional, tambahkan jika digunakan
        'isseller',
        'images',
        'alamat',
        'foto_wajah',
        'foto_ktp',//
        'foto_ttd',
        'status_verifikasi',
        'hukuman',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function fines()
{
    return $this->hasMany(Fine::class);
}

public function kategori()
{
    return $this->hasMany(Kategori::class);
}

}