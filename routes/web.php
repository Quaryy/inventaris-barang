<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PeminjamanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Semua route web aplikasi didefinisikan di sini.
| File ini otomatis dimuat oleh RouteServiceProvider.
|--------------------------------------------------------------------------
*/

// Route halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Dashboard (hanya untuk user yang sudah login & terverifikasi)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Group route dengan middleware auth
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Jika ingin fitur hapus akun, tinggal aktifkan baris di bawah
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Master Data
    |--------------------------------------------------------------------------
    */
    Route::resource('user', UserController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('lokasi', LokasiController::class);

    /*
    |--------------------------------------------------------------------------
    | Barang
    |--------------------------------------------------------------------------
    */
    // Cetak laporan barang
    Route::get('/barang/laporan', [BarangController::class, 'cetakLaporan'])->name('barang.laporan');
    Route::resource('barang', BarangController::class);

    /*
    |--------------------------------------------------------------------------
    | Peminjaman
    |--------------------------------------------------------------------------
    */
    // ✅ Letakkan laporan dulu sebelum resource
    // Peminjaman
Route::get('/peminjaman/laporan', [PeminjamanController::class, 'laporan'])->name('peminjaman.laporan');
Route::resource('peminjaman', PeminjamanController::class);

});


// Auth routes (login, register, dll)
require __DIR__ . '/auth.php';
