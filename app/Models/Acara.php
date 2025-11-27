<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acara extends Model
{
    use HasFactory;

    protected $table = 'acara';
    protected $fillable = [
        'nama_mempelai',
        'waktu_acara',
        'lokasi',
        'deskripsi',
    ];

    protected $casts = [
        'waktu_acara' => 'datetime',
    ];

    public function tamu()
    {
        return $this->hasMany(Tamu::class, 'acara_id');
    }
}