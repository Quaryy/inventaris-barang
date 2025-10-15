<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Barang extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_pengadaan' => 'date',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }

    protected $fillable = [
    'kode_barang',
    'nama_barang',
    'kategori_id',
    'lokasi_id',
    'jumlah',
    'satuan',
    'kondisi',
    'tanggal_pengadaan',
    'gambar',
    'sumber_dana', // <--- tambahkan ini
];

}
