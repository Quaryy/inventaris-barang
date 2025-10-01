<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Barang;

class PeminjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::first();
        $user2 = User::skip(1)->first(); // user kedua
        $barang1 = Barang::first();
        $barang2 = Barang::skip(1)->first();

        if ($user1 && $barang1) {
            DB::table('peminjamans')->insert([
                [
                    'barang_id' => $barang1->id,
                    'user_id' => $user1->id,
                    'tanggal_pinjam' => Carbon::now()->subDays(2),
                    'tanggal_kembali' => null,
                    'status' => 'dipinjam',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                $user2 && $barang2 ? [
                    'barang_id' => $barang2->id,
                    'user_id' => $user2->id,
                    'tanggal_pinjam' => Carbon::now()->subDays(7),
                    'tanggal_kembali' => Carbon::now()->subDays(1),
                    'status' => 'dikembalikan',
                    'created_at' => now(),
                    'updated_at' => now(),
                ] : null,
            ]);
        }
    }
}
