<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('peminjamans', 'user_id')) {
            Schema::table('peminjamans', function (Blueprint $table) {
                // coba drop foreign key dulu (bungkus try supaya tidak gagal kalau nama constraint beda)
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Throwable $e) {
                    // ignore
                }
                $table->dropColumn('user_id');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('peminjamans', 'user_id')) {
            Schema::table('peminjamans', function (Blueprint $table) {
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            });
        }
    }
};
