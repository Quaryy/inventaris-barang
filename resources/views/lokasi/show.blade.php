@extends('layouts.app')

@section('title', 'Detail Lokasi - ' . $lokasi->nama_lokasi)

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center">
            <span><i class="bi bi-geo-alt"></i> Detail Lokasi: {{ $lokasi->nama_lokasi }}</span>
            <a href="{{ route('lokasi.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card-body">
            @if ($barangs->isEmpty())
                <div class="alert alert-warning text-center my-4">
                    <i class="bi bi-exclamation-triangle"></i> Tidak ada barang yang terdaftar di lokasi ini.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="min-width: 180px;">Kode Barang</th>
                                <th style="min-width: 200px;">Nama Barang</th>
                                <th style="min-width: 150px;">Kategori</th>
                                <th style="min-width: 100px;">Jumlah</th>
                                <th style="min-width: 120px;">Kondisi</th>
                                <th style="min-width: 150px;">Sumber Dana</th>
                                <th style="min-width: 150px;">Tanggal Pengadaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barangs as $index => $barang)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $barang->kode_barang ?? '-' }}</td>
                                    <td class="text-start ps-3">{{ $barang->nama_barang }}</td>
                                    <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                                    <td><span class="badge bg-success">{{ $barang->jumlah }}</span></td>
                                    <td>
                                        @if ($barang->kondisi === 'Rusak Berat')
                                            <span class="badge bg-danger">Rusak Berat</span>
                                        @elseif ($barang->kondisi === 'Rusak Ringan')
                                            <span class="badge bg-warning text-dark">Rusak Ringan</span>
                                        @else
                                            <span class="badge bg-success">Baik</span>
                                        @endif
                                    </td>
                                    <td>{{ $barang->sumber_dana ?? '-' }}</td>
                                    <td>
                                        {{ $barang->tanggal_pengadaan
                                            ? \Carbon\Carbon::parse($barang->tanggal_pengadaan)->translatedFormat('d F Y')
                                            : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
