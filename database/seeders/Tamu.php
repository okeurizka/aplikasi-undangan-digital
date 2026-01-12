<?php

namespace Database\Seeders;

use App\Models\Tamu as ModelsTamu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class Tamu extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModelsTamu::create([
            'acara_id' => 1,
            'nama_tamu' => 'Laura Garcia',
            'alamat' => 'Jl. Pahlawan No. 7',
            'kode_unik' => Str::uuid()->toString(),
            'qr_code_string' => Str::uuid()->toString(),
            'status_undangan' => 'Diundang',
        ]);

        ModelsTamu::create([
            'acara_id' => 1,
            'nama_tamu' => 'James Rodriguez',
            'alamat' => 'Jl. Veteran No. 8',
            'kode_unik' => Str::uuid()->toString(),
            'qr_code_string' => Str::uuid()->toString(),
            'status_undangan' => 'Diundang',
        ]);

        ModelsTamu::create([
            'acara_id' => 1,
            'nama_tamu' => 'Olivia Hernandez',
            'alamat' => 'Jl. Merpati No. 9',
            'kode_unik' => Str::uuid()->toString(),
            'qr_code_string' => Str::uuid()->toString(),
            'status_undangan' => 'Diundang',
        ]);

        ModelsTamu::create([
            'acara_id' => 1,
            'nama_tamu' => 'William Lee',
            'alamat' => 'Jl. Kenanga No. 10',
            'kode_unik' => Str::uuid()->toString(),
            'qr_code_string' => Str::uuid()->toString(),
            'status_undangan' => 'Diundang',
        ]);
    }
}
