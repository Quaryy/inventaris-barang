<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        // 🔍 Query dasar dengan relasi barang
        $query = Peminjaman::with('barang');

        // 🔎 Filter pencarian (nama peminjam / nama barang)
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where('nama_peminjam', 'like', "%{$keyword}%")
                ->orWhereHas('barang', function ($q) use ($keyword) {
                    $q->where('nama_barang', 'like', "%{$keyword}%");
                });
        }

        // 🔽 Urutan data (terbaru / terlama)
        $sortOrder = $request->get('sort', 'desc');
        $query->orderBy('created_at', $sortOrder);

        // ✅ Tambahkan pagination (10 data per halaman)
        $peminjamans = $query->paginate(10)->appends($request->only('search', 'sort'));

        // ⬅️ Kirim ke view
        return view('peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $barangs = Barang::all();
        return view('peminjaman.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'nama_peminjam' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        // ❌ Cek stok cukup
        if ($barang->jumlah < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok barang tidak mencukupi.');
        }

        // ✅ Simpan peminjaman
        Peminjaman::create([
            'barang_id' => $request->barang_id,
            'nama_peminjam' => $request->nama_peminjam,
            'jumlah' => $request->jumlah,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'status' => 'dipinjam',
        ]);

        // 📉 Kurangi stok barang
        $barang->decrement('jumlah', $request->jumlah);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dicatat dan stok barang berkurang.');
    }

    public function show(Peminjaman $peminjaman)
    {
        return view('peminjaman.show', compact('peminjaman'));
    }

    public function edit(Peminjaman $peminjaman)
    {
        $barangs = Barang::all();
        return view('peminjaman.edit', compact('peminjaman', 'barangs'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        // 🔄 Tombol “Kembalikan”
        if ($request->has('kembalikan')) {
            if ($peminjaman->status === 'dipinjam') {
                $peminjaman->update([
                    'tanggal_kembali' => now(),
                    'status' => 'dikembalikan'
                ]);

                if ($peminjaman->barang) {
                    $peminjaman->barang->increment('jumlah', $peminjaman->jumlah);
                }
            }

            return redirect()->route('peminjaman.index')->with('success', 'Barang berhasil dikembalikan dan stok bertambah.');
        }

        // 📝 Update data biasa
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'nama_peminjam' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date',
            'status' => 'required|in:dipinjam,dikembalikan',
        ]);

        if ($peminjaman->barang_id == $request->barang_id) {
            $selisih = $request->jumlah - $peminjaman->jumlah;

            if ($selisih > 0) {
                if ($peminjaman->barang && $peminjaman->barang->jumlah < $selisih) {
                    return back()->with('error', 'Stok barang tidak mencukupi untuk update jumlah.');
                }
                $peminjaman->barang?->decrement('jumlah', $selisih);
            } elseif ($selisih < 0) {
                $peminjaman->barang?->increment('jumlah', abs($selisih));
            }
        } else {
            // Jika barang diganti, stok lama dikembalikan
            $peminjaman->barang?->increment('jumlah', $peminjaman->jumlah);

            $barangBaru = Barang::findOrFail($request->barang_id);
            if ($barangBaru->jumlah < $request->jumlah) {
                return back()->with('error', 'Stok barang baru tidak mencukupi.');
            }
            $barangBaru->decrement('jumlah', $request->jumlah);
        }

        $peminjaman->update($request->only([
            'barang_id',
            'nama_peminjam',
            'jumlah',
            'tanggal_pinjam',
            'tanggal_kembali',
            'status'
        ]));

        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        if ($peminjaman->status === 'dipinjam' && $peminjaman->barang) {
            $peminjaman->barang->increment('jumlah', $peminjaman->jumlah);
        }

        $peminjaman->delete();
        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }

    public function laporan()
    {
        $peminjamans = Peminjaman::with('barang')->get();
        $title = "Laporan Peminjaman";
        $date = now()->format('d-m-Y');

        return view('peminjaman.laporan', compact('peminjamans', 'title', 'date'));
    }

    // 🔎 API ringkasan barang untuk AJAX
    public function getBarang($id)
    {
        $barang = \App\Models\Barang::with(['kategori', 'lokasi'])->find($id);

        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan.']);
        }

        return response()->json([
            'nama_barang'  => $barang->nama_barang,
            'kategori'     => $barang->kategori->nama_kategori ?? '-',
            'jumlah'       => $barang->jumlah,
            'kondisi'      => $barang->kondisi,
            'lokasi'       => $barang->lokasi->nama_lokasi ?? '-',
            'sumber_dana'  => $barang->sumber_dana 
                ? "<span class='badge bg-secondary px-3 py-2 rounded-pill fw-semibold text-white'>{$barang->sumber_dana}</span>"
                : "<span class='badge bg-light text-dark px-3 py-2 rounded-pill'>-</span>",
        ]);
    }
}
