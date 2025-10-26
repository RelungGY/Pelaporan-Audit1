<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $fillable = ['seller_id','namakategori', 'status'];

    public function items()
    {
        return $this->hasMany(Item::class, 'kategori_id');
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }
}