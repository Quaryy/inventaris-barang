<x-table-list>
    <x-slot name="header">
        <tr>
            <th>#</th>
            <th>Nama Peminjam</th>
            <th>Nama Barang</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
            <th>&nbsp;</th>
        </tr>
    </x-slot>

    @forelse ($peminjamans as $index => $p)
        <tr>
            <td>{{ $peminjamans->firstItem() + $index }}</td>
            <td>{{ $p->nama_peminjam }}</td>
            <td>{{ $p->barang->nama_barang }}</td>
            <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d-m-Y') }}</td>
            <td>
                {{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d-m-Y') : '-' }}
            </td>
            <td>
                <span class="badge {{ $p->status == 'dipinjam' ? 'bg-warning text-dark' : 'bg-success' }}">
                    {{ ucfirst($p->status) }}
                </span>
            </td>
            <td class="text-end">
                <x-tombol-aksi href="{{ route('peminjaman.show',$p->id) }}" type="show" />
                <x-tombol-aksi href="{{ route('peminjaman.edit',$p->id) }}" type="edit" />

                @if($p->status == 'dipinjam')
                    {{-- Tombol buka modal --}}
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalKembalikan{{ $p->id }}">
                        <i class="bi bi-arrow-return-left"></i>
                    </button>

                    {{-- Modal konfirmasi --}}
                    <div class="modal fade" id="modalKembalikan{{ $p->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        Konfirmasi Pengembalian
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-start"> {{-- 🔹 isi modal rata kiri --}}
                                    <p>
                                        Apakah Anda yakin ingin mengembalikan barang ini?
                                    </p>
                                    <ul class="mb-0">
                                        <li><strong>Peminjam:</strong> {{ $p->nama_peminjam }}</li>
                                        <li><strong>Barang:</strong> {{ $p->barang->nama_barang }}</li>
                                        <li><strong>Jumlah:</strong> {{ $p->jumlah }}</li>
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                        Batal
                                    </button>
                                    <form action="{{ route('peminjaman.update',$p->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PUT')
                                        <button name="kembalikan" class="btn btn-success btn-sm">
                                            <i class="bi bi-check-circle"></i> Ya, Kembalikan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <x-tombol-aksi 
                    href="{{ route('peminjaman.destroy',$p->id) }}" 
                    type="delete" 
                />
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center">
                <div class="alert alert-danger mb-0">
                    Belum ada data peminjaman.
                </div>
            </td>
        </tr>
    @endforelse
</x-table-list>

{{-- Pagination --}}
<div class="mt-3">
    {{ $peminjamans->links() }}
</div>
