<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    
    protected $fillable = [
        'user_id',
        'sender_id',
        'type',
        'message',
        'is_read',
    ];

    // Relasi ke User sebagai penerima
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke User sebagai pengirim
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}