<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #000;
        }

        h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }

        p {
            margin: 4px 0 15px 0;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 12px;
        }

        th {
            background-color: #f0f0f0; /* ✅ warna abu seperti modul inventaris */
            text-align: center;
            font-weight: bold;
        }

        td {
            vertical-align: top;
        }

        td.text-left {
            text-align: left;
        }

        td.text-center {
            text-align: center;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin-bottom: 4px;
        }

        .empty-row {
            text-align: center;
            font-style: italic;
            color: #666;
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2>{{ $title }}</h2>
        <p>Tanggal Cetak: {{ $date }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 120px;">Nama Peminjam</th>
                <th style="width: 130px;">Nama Barang</th>
                <th style="width: 100px;">Sumber Dana</th>
                <th style="width: 60px;">Jumlah</th>
                <th style="width: 90px;">Tanggal Pinjam</th>
                <th style="width: 90px;">Tanggal Kembali</th>
                <th style="width: 80px;">Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($peminjamans as $index => $peminjaman)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-left">{{ $peminjaman->nama_peminjam }}</td>
                    <td class="text-left">{{ $peminjaman->barang->nama_barang ?? '-' }}</td>
                    <td class="text-left">{{ $peminjaman->barang->sumber_dana ?? '-' }}</td>
                    <td class="text-center">{{ $peminjaman->jumlah }}</td>
                    <td class="text-center">{{ date('d-m-Y', strtotime($peminjaman->tanggal_pinjam)) }}</td>
                    <td class="text-center">
                        {{ $peminjaman->tanggal_kembali 
                            ? date('d-m-Y', strtotime($peminjaman->tanggal_kembali)) 
                            : '-' }}
                    </td>
                    <td class="text-center">{{ ucfirst($peminjaman->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="empty-row">Tidak ada data peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
