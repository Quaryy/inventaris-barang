<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hanya tambahkan kalau belum ada
        if (! Schema::hasColumn('peminjamans', 'nama_peminjam')) {
            Schema::table('peminjamans', function (Blueprint $table) {
                $table->string('nama_peminjam')->after('barang_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('peminjamans', 'nama_peminjam')) {
            Schema::table('peminjamans', function (Blueprint $table) {
                $table->dropColumn('nama_peminjam');
            });
        }
    }
};
