<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        'nama_pelanggan',
        'item_id',
        'nomor_telepon',
        'tanggal_pemesanan',
        'tanggal_event',
        'jenis_layanan',
        'total_harga',
        'status_pembayaran',
        'keterangan',
        'jumlah_pesanan',
        'status_pengembalian'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

}