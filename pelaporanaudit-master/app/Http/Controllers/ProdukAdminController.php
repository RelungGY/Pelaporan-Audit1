<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Notification;

class ProdukAdminController extends Controller
{
    // Menampilkan data item
    public function index()
{
    $item = Item::all(); // Tambahkan count item yang terkait
    $data = [
        'content' => 'admin.produkadmin',
        'item' => $item,
    ];
    return view('admin.layouts.index', ['data' => $data]);
}


    // Memperbarui status item
    public function updateStatus(Request $request)
    {
        $item = Item::find($request->id); // Cari data berdasarkan ID
        if ($item) {
            $item->status = $request->status; // Update status
            $item->save(); // Simpan perubahan
            return response()->json(['message' => 'Status berhasil diperbarui!']);
        }
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    public function updateStatusBanned(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'status_verifikasi' => 'required|in:pending,approved,rejected',
            'message' => 'nullable|required_if:status_verifikasi,rejected|max:255',
        ]);

        // Ambil data denda berdasarkan ID
        $fine = Notification::findOrFail($id);

        // Update status verifikasi
        $fine->status = $request->status_verifikasi;

        // Jika status rejected, tambahkan alasan penolakan
        if ($request->status_verifikasi === 'rejected') {
            $fine->message = $request->message;
        } else {
            // Kosongkan message jika status bukan rejected
            $fine->message = null;
        }

        // Simpan perubahan ke database
        $fine->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Status denda berhasil diperbarui.');
    }
}