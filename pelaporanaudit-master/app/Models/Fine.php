<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    use HasFactory;

    protected $table = 'fines';

    protected $fillable = [
        'user_id',
        'payment_proof',
        'status',
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}

}