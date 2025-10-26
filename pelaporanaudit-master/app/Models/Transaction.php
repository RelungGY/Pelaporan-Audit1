<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'item_id',
        'user_id',
        'start_date',
        'end_date',
        'deadline',
        'total_amount',
        'status',
        'status_confirm',
        'bukti_pembayaran',
        'tanggal_pembayaran',
        'bukti_pengembalian'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}