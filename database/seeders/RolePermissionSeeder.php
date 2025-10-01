<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;


class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat permissions
        Permission::create(['name' => 'manage barang']);
        Permission::create(['name' => 'delete barang']);
        Permission::create(['name' => 'view kategori']);
        Permission::create(['name' => 'manage kategori']);
        Permission::create(['name' => 'view lokasi']);
        Permission::create(['name' => 'manage lokasi']);

        // Buat role
        $petugasRole = Role::create(['name' => 'petugas']);
        $adminRole   = Role::create(['name' => 'admin']);

        // Set permission untuk role petugas
        $petugasRole->givePermissionTo([
            'manage barang',
            'view kategori',
            'view lokasi',
        ]);

        // Set permission untuk role admin (semua permission)
        $adminRole->givePermissionTo(Permission::all());
    }
}

