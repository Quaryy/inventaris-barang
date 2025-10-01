@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Edit Peminjaman</h3>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('peminjaman.update', $peminjaman->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <x-form-select 
                            label="Barang" 
                            name="barang_id" 
                            :value="old('barang_id', $peminjaman->barang_id)" 
                            :option-data="$barangs" 
                            option-label="nama_barang" 
                            option-value="id"
                            :option-extra="fn($barang) => '(stok: ' . $barang->jumlah . ')'"
                            required
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form-input 
                            label="Nama Peminjam" 
                            name="nama_peminjam" 
                            :value="old('nama_peminjam', $peminjaman->nama_peminjam)" 
                            required 
                        />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <x-form-input 
                            label="Jumlah Barang Dipinjam" 
                            name="jumlah" 
                            type="number" 
                            min="1" 
                            :value="old('jumlah', $peminjaman->jumlah)" 
                            required 
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form-input 
                            label="Tanggal Pinjam" 
                            name="tanggal_pinjam" 
                            type="date" 
                            :value="old('tanggal_pinjam', $peminjaman->tanggal_pinjam->format('Y-m-d'))" 
                            required 
                        />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <x-form-input 
                            label="Tanggal Kembali" 
                            name="tanggal_kembali" 
                            type="date" 
                            :value="old('tanggal_kembali', $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('Y-m-d') : '')" 
                        />
                    </div>
                    <div class="col-md-6">
                        @php
                            $statusOptions = [
                                ['value' => 'dipinjam', 'label' => 'Dipinjam'],
                                ['value' => 'dikembalikan', 'label' => 'Dikembalikan'],
                            ];
                        @endphp
                        <x-form-select 
                            label="Status" 
                            name="status" 
                            :value="old('status', $peminjaman->status)" 
                            :option-data="$statusOptions" 
                            option-label="label" 
                            option-value="value" 
                            required
                        />
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <x-tombol-kembali :href="route('peminjaman.index')" class="me-2" />
                    <x-primary-button>
                        <i class="bi bi-check-circle"></i> Update
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
