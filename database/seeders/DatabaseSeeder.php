<?php

namespace Database\Seeders;

use App\Models\Acara;
use App\Models\Tamu;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed User Administrator
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'Administrator',
        ]);

        // 2. Seed User Petugas
        User::create([
            'username' => 'petugas',
            'password' => Hash::make('petugas123'),
            'role' => 'Petugas',
        ]);

        // 3. Seed Data Acara (Penting karena Tamu butuh acara_id)
        $acara = Acara::create([
            'nama_mempelai' => 'Riz & Partner',
            'waktu_acara' => now()->addDays(7),
            'lokasi' => 'Gedung Serbaguna Purwosari',
            'deskripsi' => 'Resepsi Pernikahan',
        ]);

        // 4. Seed Data Tamu
        Tamu::create([
            'acara_id' => $acara->id,
            'nama_tamu' => 'Makani (Guest Star)',
            'alamat' => 'Jakarta',
            'kode_unik' => Str::uuid()->toString(),
            'qr_code_string' => Str::uuid()->toString(),
            'status_undangan' => 'Diundang',
        ]);

        // $this->command->info('Mantap Riz! Akun Admin & Petugas udah siap.');
    }
}
