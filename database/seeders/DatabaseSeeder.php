<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            KategoriSeeder::class,
            LokasiSeeder::class,
            BarangSeeder::class,
        ]);

        // Buat user admin
        $admin = User::factory()->create([
            'name'  => 'Administrator',
            'email' => 'admin@mail.com',
            'password' => bcrypt('password'),
        ]);

        // Buat user petugas
        $petugas = User::factory()->create([
            'name'  => 'Petugas Inventaris',
            'email' => 'petugas@mail.com',
            'password' => bcrypt('password'),
        ]);

        // Assign role ke user
        $admin->assignRole('admin');
        $petugas->assignRole('petugas');

        // Jalankan seeder peminjaman setelah user & barang tersedia
        $this->call([
            PeminjamanSeeder::class,
        ]);
    }
}
