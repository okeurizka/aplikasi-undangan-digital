<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Acara as AcaraSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'username' => 'petugas1',
            'password' => Hash::make('petugas123'),
            'role' => 'Petugas',
        ]);

        User::create([
            'username' => 'petugas2',
            'password' => Hash::make('petugas123'),
            'role' => 'Petugas',
        ]);

        $this->call([
            AcaraSeeder::class,
            Tamu::class,
        ]);
    }
}
