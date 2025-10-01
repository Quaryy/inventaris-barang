{{-- Error Validation --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-circle"></i> Terjadi kesalahan:
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>- {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ isset($peminjaman) ? route('peminjaman.update', $peminjaman->id) : route('peminjaman.store') }}" method="POST">
    @csrf
    @if(isset($peminjaman))
        @method('PUT')
    @endif

    <div class="row mb-3">
        <div class="col-md-6">
            <x-form-select 
                label="Barang" 
                name="barang_id" 
                :value="old('barang_id', $peminjaman->barang_id ?? '')" 
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
                :value="old('nama_peminjam', $peminjaman->nama_peminjam ?? '')" 
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
                :value="old('jumlah', $peminjaman->jumlah ?? 1)" 
                required 
            />
        </div>
        <div class="col-md-6">
            <x-form-input 
                label="Tanggal Pinjam" 
                name="tanggal_pinjam" 
                type="date" 
                :value="old('tanggal_pinjam', isset($peminjaman) ? $peminjaman->tanggal_pinjam->format('Y-m-d') : '')" 
                required 
            />
        </div>
    </div>

    @if(isset($peminjaman))
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
                    :value="old('status', $peminjaman->status ?? '')" 
                    :option-data="$statusOptions" 
                    option-label="label" 
                    option-value="value" 
                />
            </div>
        </div>
    @endif

    <div class="mt-4">
        <x-primary-button>
            {{ isset($peminjaman) ? __('Update') : __('Simpan') }}
        </x-primary-button>
        <x-tombol-kembali :href="route('peminjaman.index')" />
    </div>
</form>
