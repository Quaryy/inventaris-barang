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
        }

        p {
            margin: 4px 0 15px 0;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 12px;
        }

        th {
            text-align: center;
            font-weight: bold;
            background-color: #f9f9f9;
        }

        td {
            text-align: center;
        }

        td.text-left {
            text-align: left;
        }

        .empty-row {
            text-align: center;
            font-style: italic;
            color: #555;
        }
    </style>
</head>
<body onload="window.print()">

    <div style="text-align: center;">
        <h2>{{ $title }}</h2>
        <p>Tanggal Cetak: {{ $date }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($peminjamans as $index => $peminjaman)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">{{ $peminjaman->nama_peminjam }}</td>
                    <td class="text-left">{{ $peminjaman->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $peminjaman->jumlah }}</td>
                    <td>{{ date('d-m-Y', strtotime($peminjaman->tanggal_pinjam)) }}</td>
                    <td>
                        {{ $peminjaman->tanggal_kembali 
                            ? date('d-m-Y', strtotime($peminjaman->tanggal_kembali)) 
                            : '-' }}
                    </td>
                    <td>{{ ucfirst($peminjaman->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty-row">Tidak ada data peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
