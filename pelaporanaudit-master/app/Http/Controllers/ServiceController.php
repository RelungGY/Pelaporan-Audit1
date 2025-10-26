<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Item;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $pemesanan = Service::all();
        $data = [
            'content' => 'seller.service.service',
            'pemesanan' => $pemesanan // Pastikan data kategori dikirimkan
        ];
        return view('seller.layouts.index', ['data' => $data]);
    }

    // Menampilkan form untuk membuat pemesanan baru
    public function create()
    {
        $items = Item::where('seller_id', auth()->id())->where('stok', '>', 0)->get();

        $data = [
            'content' => 'seller.service.tambah',
            'items' => $items // Pastikan data kategori dikirimkan
        ];
        return view('seller.layouts.index', ['data' => $data]);
    }

    // Menyimpan data pemesanan ke database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'nomor_telepon' => 'nullable|string|max:20',
            'tanggal_pemesanan' => 'required|date',
            'tanggal_event' => 'nullable|date',
            'jenis_layanan' => 'required|string|max:100',
            'total_harga' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:Belum Dibayar,Sudah Dibayar',
            'keterangan' => 'nullable|string',
            'item_id' => 'nullable|exists:items,id', // Validasi item_id
            'jumlah_pesanan' => 'nullable|integer|min:1',
        ]);

        // Cari item berdasarkan item_id
        $item = Item::find($validatedData['item_id']);
        
        if ($item) {
            // Periksa apakah stok cukup
            if ($item->stok < $validatedData['jumlah_pesanan']) {
                return redirect()->back()->withErrors(['jumlah_pesanan' => 'Stok tidak mencukupi untuk pesanan ini.']);
            }

            // Kurangi stok
            $item->stok -= $validatedData['jumlah_pesanan'];
            if ($item->stok == 0) {
                $item->available = 0;
            }
            $item->save();
        }

        // Simpan data pemesanan
        $validatedData['status_pengembalian'] = 'dipinjam';
        $pemesanan = Service::create($validatedData);
        $data = [
            'pemesanan' => $pemesanan,
            'item' => $item,
        ];

        // Buat PDF dari view invoice
        $pdf = \PDF::loadView('seller.service.invoice', $data);
        $fileName = 'invoice-pemesanan-' . $pemesanan->id . '.pdf';
        $filePath = storage_path('app/public/' . $fileName);
        $pdf->save($filePath);

        // Redirect ke halaman index dengan parameter untuk mendownload file
        return redirect()->route('service.index')->with('download', $fileName);
    }


    // Menampilkan detail pemesanan berdasarkan ID
    public function show($id)
    {
        $pemesanan = Service::findOrFail($id);
        // $items = Item::where('seller_id', auth()->id())->get();
        $item = Item::find($pemesanan->item_id);
        $data = [
            'content' => 'seller.service.detail',
            'pemesanan' => $pemesanan,
            'item' => $item
        ];
        return view('seller.layouts.index', ['data' => $data]);
    }

    // Menampilkan form edit untuk pemesanan
    public function edit($id)
    {
        $pemesanan = Service::findOrFail($id);
        $items = Item::where('seller_id', auth()->id())->where('stok', '>', 0)->get();
        $data = [
            'content' => 'seller.service.edit',
            'service' => $pemesanan,
            'items' => $items
        ];
        return view('seller.layouts.index', ['data' => $data]);
    }

    // Mengupdate data pemesanan
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
    'nama_pelanggan' => 'required|string|max:100',
    'nomor_telepon' => 'nullable|string|max:20',
    'tanggal_pemesanan' => 'required|date',
    'tanggal_event' => 'nullable|date',
    'jenis_layanan' => 'required|string|max:100',
    'total_harga' => 'required|numeric|min:0',
    'status_pembayaran' => 'required|in:Belum Dibayar,Sudah Dibayar',
    'keterangan' => 'nullable|string',
    'item_id' => 'nullable|exists:items,id', // Validasi item_id
    'jumlah_pesanan' => 'nullable|integer|min:1',
]);

        $pemesanan = Service::findOrFail($id);

        // Jika item_id diubah, periksa stok dan kurangi stok baru
        if ($request->has('item_id') && $request->item_id != $pemesanan->item_id) {
            $newItem = Item::find($validatedData['item_id']);
            if ($newItem && $newItem->stok < $validatedData['jumlah_pesanan']) {
            return redirect()->back()->withErrors(['jumlah_pesanan' => 'Stok tidak mencukupi untuk pesanan ini.']);
            }

            // Kembalikan stok item lama
            if ($pemesanan->item_id) {
            $oldItem = Item::find($pemesanan->item_id);
            if ($oldItem) {
                $oldItem->stok += $pemesanan->jumlah_pesanan;
                if ($oldItem->stok > 0) {
                $oldItem->available = 1;
                }
                $oldItem->save();
            }
            }

            // Kurangi stok item baru
            if ($newItem) {
            $newItem->stok -= $validatedData['jumlah_pesanan'];
            if ($newItem->stok == 0) {
                $newItem->available = 0;
            }
            $newItem->save();
            }
        } elseif ($request->has('jumlah_pesanan') && $request->jumlah_pesanan != $pemesanan->jumlah_pesanan) {
            // Jika jumlah_pesanan diubah, periksa stok dan sesuaikan stok
            $item = Item::find($pemesanan->item_id);
            if ($item && $item->stok + $pemesanan->jumlah_pesanan < $validatedData['jumlah_pesanan']) {
            return redirect()->back()->withErrors(['jumlah_pesanan' => 'Stok tidak mencukupi untuk pesanan ini.']);
            }

            // Sesuaikan stok
            if ($item) {
            $item->stok += $pemesanan->jumlah_pesanan - $validatedData['jumlah_pesanan'];
            if ($item->stok == 0) {
                $item->available = 0;
            } else {
                $item->available = 1;
            }
            $item->save();
            }
        }

        $pemesanan->update($validatedData);

        return redirect()->route('service.index')->with('success', 'Pemesanan berhasil diperbarui.');
    }

    // Menghapus data pemesanan
    public function destroy($id)
    {
        $pemesanan = Service::findOrFail($id);
        $pemesanan->delete();

        return redirect()->route('service.index')->with('success', 'Pemesanan berhasil dihapus.');
    }


    public function cetakPdf($id)
{
    $pemesanan = Service::findOrFail($id);
    $item = Item::find($pemesanan->item_id);

    $data = [
        'pemesanan' => $pemesanan,
        'item' => $item
    ];

    // Load view dan konversi ke PDF
    $pdf = \PDF::loadView('seller.service.pdf', $data);
    return $pdf->stream('pemesanan-' . $pemesanan->id . '.pdf'); // Menampilkan di browser
    // return $pdf->download('pemesanan-' . $pemesanan->id . '.pdf'); // Unduh langsung
}
public function generateInvoice($id)
{
    $pemesanan = Service::findOrFail($id);
    $item = Item::find($pemesanan->item_id);

    $data = [
        'pemesanan' => $pemesanan,
        'item' => $item,
    ];

    // Load view invoice dan konversi ke PDF
    $pdf = \PDF::loadView('seller.service.invoice', $data);
    return $pdf->stream('invoice-pemesanan-' . $pemesanan->id . '.pdf'); // Tampilkan di browser
    // return $pdf->download('invoice-pemesanan-' . $pemesanan->id . '.pdf'); // Unduh langsung
}

}