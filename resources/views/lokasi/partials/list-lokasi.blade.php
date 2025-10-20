<x-table-list>
    <x-slot name="header">
        <tr>
            <th>#</th>
            <th>Nama Lokasi</th>
            <th>Aksi</th>
        </tr>
    </x-slot>

    @forelse ($lokasis as $index => $lokasi)
        <tr>
            <td>{{ $lokasis->firstItem() + $index }}</td>
            <td>{{ $lokasi->nama_lokasi }}</td>
            <td class="d-flex gap-1">
                <a href="{{ route('lokasi.show', $lokasi->id) }}" class="btn btn-info btn-sm">
                    <i class="bi bi-eye"></i> Detail
                </a>

                @can('manage lokasi')
                    <x-tombol-aksi 
                        :href="route('lokasi.edit', $lokasi->id)" 
                        type="edit" 
                    />
                    <x-tombol-aksi 
                        :href="route('lokasi.destroy', $lokasi->id)" 
                        type="delete" 
                    />
                @endcan
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="3" class="text-center">
                <div class="alert alert-danger">
                    Data lokasi belum tersedia.
                </div>
            </td>
        </tr>
    @endforelse
</x-table-list>
