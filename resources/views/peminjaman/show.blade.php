<x-main-layout :title-page="__('Detil Peminjaman')">
    <div class="card my-5">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center">
                    @include('peminjaman.partials.info-gambar')
                </div>
                <div class="col-md">
                    @include('peminjaman.partials.info-data')
                </div>
            </div>
            <div class="mt-5">
                <a href="{{ route('peminjaman.edit', $peminjaman->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
                <x-tombol-kembali :href="route('peminjaman.index')" />
            </div>
        </div>
    </div>
</x-main-layout>
