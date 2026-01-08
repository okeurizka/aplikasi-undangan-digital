<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogCheckin extends Model
{
    use HasFactory;

    protected $table = 'log_checkin';

    protected $fillable = [
        'tamu_id',
        'acara_id',
        'petugas_id',
        'waktu_scan',
    ];

    protected $casts = [
        'waktu_scan' => 'datetime',
    ];

    public function tamu()
    {
        return $this->belongsTo(Tamu::class, 'tamu_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
