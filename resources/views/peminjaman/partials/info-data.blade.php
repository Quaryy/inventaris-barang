<h5 class="mb-3">Detail Peminjaman</h5>

<table class="table table-sm table-borderless">
    <tr>
        <th style="width: 150px;">Nama Peminjam</th>
        <td>: {{ $peminjaman->nama_peminjam }}</td>
    </tr>
    <tr>
        <th>Barang</th>
        <td>: {{ $peminjaman->barang->nama_barang ?? '-' }}</td>
    </tr>
    <tr>
        <th>Jumlah</th>
        <td>: {{ $peminjaman->jumlah }}</td>
    </tr>
    <tr>
        <th>Tanggal Pinjam</th>
        <td>: {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d-m-Y') }}</td>
    </tr>
    <tr>
        <th>Tanggal Kembali</th>
        <td>: 
            {{ $peminjaman->tanggal_kembali 
                ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d-m-Y') 
                : '-' }}
        </td>
    </tr>
    <tr>
        <th>Status</th>
        <td>: 
            <span class="badge {{ $peminjaman->status == 'dipinjam' ? 'bg-warning text-dark' : 'bg-success' }}">
                {{ ucfirst($peminjaman->status) }}
            </span>
        </td>
    </tr>
</table>
