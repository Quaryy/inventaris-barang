{{-- Error Validation --}}
@if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm">
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

    <div class="row">
        <!-- ========================= -->
        <!-- Bagian kiri (form input) -->
        <!-- ========================= -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 p-3 mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <x-form-select 
                            label="Barang" 
                            name="barang_id" 
                            :value="old('barang_id', $peminjaman->barang_id ?? '')" 
                            :option-data="$barangs" 
                            option-label="nama_barang" 
                            option-value="id"
                            :option-extra="fn($barang) => '(stok: ' . $barang->jumlah . ')' "
                            required
                        />
                    </div>

                    <div class="mb-3">
                        <x-form-input 
                            label="Nama Peminjam" 
                            name="nama_peminjam" 
                            :value="old('nama_peminjam', $peminjaman->nama_peminjam ?? '')" 
                            required 
                        />
                    </div>

                    <div class="mb-3">
                        <x-form-input 
                            label="Jumlah Barang Dipinjam" 
                            name="jumlah" 
                            type="number" 
                            min="1"
                            :value="old('jumlah', $peminjaman->jumlah ?? 1)" 
                            required 
                        />
                    </div>

                    <div class="mb-3">
                        <x-form-input 
                            label="Tanggal Pinjam" 
                            name="tanggal_pinjam" 
                            type="date" 
                            :value="old('tanggal_pinjam', isset($peminjaman) ? $peminjaman->tanggal_pinjam->format('Y-m-d') : '')" 
                            required 
                        />
                    </div>

                    @if(isset($peminjaman))
                        <div class="mb-3">
                            <x-form-input 
                                label="Tanggal Kembali" 
                                name="tanggal_kembali" 
                                type="date" 
                                :value="old('tanggal_kembali', $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('Y-m-d') : '')" 
                            />
                        </div>

                        <div class="mb-3">
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
                    @endif

                    <div class="mt-3">
                        <x-primary-button>
                            {{ isset($peminjaman) ? __('Update') : __('Simpan') }}
                        </x-primary-button>
                        <x-tombol-kembali :href="route('peminjaman.index')" />
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================= -->
        <!-- Bagian kanan (ringkasan barang) -->
        <!-- ========================= -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0" id="card-ringkasan" style="display: none;">
                <div class="card-header bg-primary text-white fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-box-seam fs-5"></i>
                    Ringkasan Barang
                </div>
                <div class="card-body" id="ringkasan-barang">
                    {{-- Konten ringkasan akan dimuat via JS --}}
                </div>
            </div>
        </div>
    </div>
</form>

{{-- ========================= --}}
{{-- Script AJAX untuk ringkasan --}}
{{-- ========================= --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectBarang = document.querySelector('select[name="barang_id"]');
    const ringkasanContainer = document.querySelector('#ringkasan-barang');
    const cardRingkasan = document.querySelector('#card-ringkasan');

    // Fungsi untuk menampilkan / menyembunyikan card ringkasan
    function toggleRingkasan(show) {
        cardRingkasan.style.display = show ? 'block' : 'none';
    }

    // Fungsi untuk memuat data barang
    function loadBarangData(barangId) {
        if (!barangId) {
            toggleRingkasan(false);
            return;
        }

        fetch(`/peminjaman/barang/${barangId}`)
            .then(response => response.json())
            .then(data => {
                toggleRingkasan(true);

                if (data.message) {
                    ringkasanContainer.innerHTML = `
                        <div class="alert alert-danger border-0 shadow-sm text-center">
                            <i class="bi bi-exclamation-triangle"></i> ${data.message}
                        </div>`;
                    return;
                }

                // Tampilkan tabel ringkasan barang lengkap
                ringkasanContainer.innerHTML = `
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th width="35%">Detail</th>
                                    <th>Informasi</th>
                                </tr>
                            </thead>
                            <tbody class="table-borderless">
                                <tr>
                                    <th>Nama Barang</th>
                                    <td>${data.nama_barang}</td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td>${data.kategori}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah Tersedia</th>
                                    <td><span class="badge bg-success">${data.jumlah}</span></td>
                                </tr>
                                <tr>
                                    <th>Kondisi</th>
                                    <td>
                                        ${
                                            data.kondisi === 'Rusak Berat' 
                                            ? '<span class="badge bg-danger">Rusak Berat</span>' :
                                            data.kondisi === 'Rusak Ringan'
                                            ? '<span class="badge bg-warning text-dark">Rusak Ringan</span>' :
                                            '<span class="badge bg-success">Baik</span>'
                                        }
                                    </td>
                                </tr>
                                <tr>
                                    <th>Lokasi</th>
                                    <td>${data.lokasi}</td>
                                </tr>
                                <tr>
                                    <th>Sumber Dana</th>
                                    <td>${data.sumber_dana ? data.sumber_dana : '<span class="text-muted">-</span>'}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                `;
            })
            .catch(err => {
                console.error(err);
                toggleRingkasan(true);
                ringkasanContainer.innerHTML = `
                    <div class="alert alert-danger border-0 shadow-sm text-center">
                        <i class="bi bi-exclamation-circle"></i> Gagal memuat data barang.
                    </div>`;
            });
    }

    // Ketika user memilih barang
    selectBarang.addEventListener('change', function() {
        loadBarangData(this.value);
    });

    // Saat reload (edit atau error validasi)
    const oldBarangId = "{{ old('barang_id') }}";
    const editBarangId = "{{ isset($peminjaman) ? $peminjaman->barang_id : '' }}";
    const barangIdToLoad = oldBarangId || editBarangId;

    if (barangIdToLoad) {
        loadBarangData(barangIdToLoad);
    } else {
        toggleRingkasan(false);
    }
});
</script>
