@if ($peminjaman->barang && $peminjaman->barang->gambar)
    <img src="{{ asset('gambar-barang/' . $peminjaman->barang->gambar) }}" 
         class="img-fluid rounded border"
         alt="{{ $peminjaman->barang->nama_barang }}" 
         style="max-height: 300px;">
@else
    <div class="d-flex align-items-center justify-content-center bg-light rounded border"
         style="height: 300px;">
        <span class="text-muted">Tidak Ada Gambar</span>
    </div>
@endif
