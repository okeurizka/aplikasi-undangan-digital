<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tamu extends Model
{
    use HasFactory;

    protected $table = 'tamu';
    protected $fillable = [
        'acara_id',
        'nama_tamu',
        'alamat',
        'kode_unik',
        'qr_code_string',
        'status_undangan',
    ];

    protected $casts = [
        'status_undangan' => 'string',
    ];

    public function acara()
    {
        return $this->belongsTo(Acara::class, 'acara_id');
    }

    public function kehadiranRsvp()
    {
        return $this->hasOne(KehadiranRsvp::class, 'tamu_id');
    }
    public function logCheckins()
    {
        return $this->hasMany(LogCheckin::class, 'tamu_id');
    }
}