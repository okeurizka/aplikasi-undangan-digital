<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KehadiranRSVP extends Model
{
    use HasFactory;
    protected $table = 'kehadiran_rsvp';
    protected $fillable = [
        'tamu_id',
        'status_kehadiran',
        'jumlah_orang',
        'ucapan_doa',
        'waktu_input',
    ];
    protected $casts = [
        'waktu_input' => 'datetime',
        'status_kehadiran' => 'string',
    ];

    public function tamu()
    {
        return $this->belongsTo(Tamu::class, 'tamu_id');
    } 
}