<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MidtransTransaction extends Model
{
    use HasFactory;

    protected $table = 'midtrans_transactions';

    protected $fillable = [
        'order_id',
        'transaction_status',
        'payment_type',
        'fraud_status',
        'gross_amount',
        'user_id',
    ];
}