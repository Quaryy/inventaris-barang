<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with('barang');

        // 🔍 Fitur pencarian nama peminjam atau barang
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where('nama_peminjam', 'like', "%{$keyword}%")
                  ->orWhereHas('barang', function($q) use ($keyword) {
                      $q->where('nama_barang', 'like', "%{$keyword}%");
                  });
        }

        $peminjamans = $query->latest()->paginate(10);
        $peminjamans->appends($request->only('search')); // pagination tetap bawa keyword

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

        // ✅ Catat peminjaman
        Peminjaman::create([
            'barang_id' => $request->barang_id,
            'nama_peminjam' => $request->nama_peminjam,
            'jumlah' => $request->jumlah,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'status' => 'dipinjam',
        ]);

        // 📉 Kurangi stok barang sesuai jumlah
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
        // 🔄 Tombol Kembalikan
        if ($request->has('kembalikan')) {
            if ($peminjaman->status === 'dipinjam') {
                $peminjaman->update([
                    'tanggal_kembali' => now(),
                    'status' => 'dikembalikan'
                ]);

                // 📈 Tambah stok kembali sesuai jumlah pinjaman (jika barang masih ada)
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

        // 🔄 Hitung selisih jumlah jika barang_id sama
        if ($peminjaman->barang_id == $request->barang_id) {
            $selisih = $request->jumlah - $peminjaman->jumlah;

            if ($selisih > 0) {
                // Kurangi stok tambahan
                if ($peminjaman->barang && $peminjaman->barang->jumlah < $selisih) {
                    return back()->with('error', 'Stok barang tidak mencukupi untuk update jumlah.');
                }
                if ($peminjaman->barang) {
                    $peminjaman->barang->decrement('jumlah', $selisih);
                }
            } elseif ($selisih < 0) {
                // Kembalikan stok jika jumlah dikurangi
                if ($peminjaman->barang) {
                    $peminjaman->barang->increment('jumlah', abs($selisih));
                }
            }
        } else {
            // Jika barang diganti, kembalikan stok lama
            if ($peminjaman->barang) {
                $peminjaman->barang->increment('jumlah', $peminjaman->jumlah);
            }

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
        // 🔁 Jika masih dipinjam → stok dikembalikan dulu (jika barang masih ada)
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

    return view('peminjaman.laporan', compact('peminjamans','title','date'));
}

}
