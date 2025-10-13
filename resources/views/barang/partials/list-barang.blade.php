<!-- Tampilan filter urutan (versi simpel & rapi) -->
<div class="d-flex justify-content-end mb-3">
    <form action="{{ route('barang.index') }}" method="GET" class="d-flex align-items-center gap-2">
        <select name="sort" id="sort" class="form-select form-select-sm w-auto shadow-sm border-0 bg-light fw-semibold" onchange="this.form.submit()">
            <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>🔽 Barang Terbaru</option>
            <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>🔼 Barang Lama</option>
        </select>
    </form>
</div>

<x-table-list>
    <x-slot name="header">
        <tr>
            <th>#</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Lokasi</th>
            <th>Jumlah</th>
            <th>Kondisi</th>
            <th>&nbsp;</th>
        </tr>
    </x-slot>

    @forelse ($barangs as $index => $barang)
        <tr>
            <td>{{ $barangs->firstItem() + $index }}</td>
            <td>{{ $barang->kode_barang }}</td>
            <td>{{ $barang->nama_barang }}</td>
            <td>{{ $barang->kategori->nama_kategori }}</td>
            <td>{{ $barang->lokasi->nama_lokasi }}</td>
            <td>{{ $barang->jumlah }} {{ $barang->satuan }}</td>
            <td>
                @php
                    $badgeClass = 'bg-info';
                    if ($barang->kondisi == 'Rusak Ringan') {
                        $badgeClass = 'bg-warning text-dark';
                    } elseif ($barang->kondisi == 'Rusak Berat') {
                        $badgeClass = 'bg-danger';
                    }
                @endphp

                <span class="badge {{ $badgeClass }}">
                    {{ $barang->kondisi }}
                </span>
            </td>
            <td class="text-end">
                @can('manage barang')
                    <x-tombol-aksi href="{{ route('barang.show', $barang->id) }}" type="show" />
                    <x-tombol-aksi href="{{ route('barang.edit', $barang->id) }}" type="edit" />
                @endcan
                @can('delete barang')
                    <x-tombol-aksi 
                        href="{{ route('barang.destroy', $barang->id) }}" 
                        type="delete" 
                    />
                @endcan
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center">
                <div class="alert alert-danger mb-0">
                    Data barang belum tersedia.
                </div>
            </td>
        </tr>
    @endforelse
</x-table-list>

<!-- Pagination -->
<div class="mt-3">
    {{ $barangs->links() }}
</div>
