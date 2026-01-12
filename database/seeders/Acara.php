<?php

namespace Database\Seeders;

use App\Models\Acara as ModelsAcara;
use Illuminate\Database\Seeder;

class Acara extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModelsAcara::create([
            'nama_mempelai' => 'Abdul & Partner',
            'waktu_acara' => now()->addDays(7),
            'lokasi' => 'Gedung Serbaguna Purwosari',
            'deskripsi' => 'Resepsi Pernikahan',
            'petugas_id' => 2,
        ]);

    }
}
