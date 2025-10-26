<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriAdminController extends Controller
{
    // Menampilkan data kategori
    public function index()
{
    $kategori = Kategori::withCount('items')->get(); // Tambahkan count item yang terkait
    $data = [
        'content' => 'admin.kategoriadmin',
        'kategori' => $kategori,
    ];
    return view('admin.layouts.index', ['data' => $data]);
}


    // Memperbarui status kategori
    public function updateStatus(Request $request)
    {
        $kategori = Kategori::find($request->id); // Cari data berdasarkan ID
        if ($kategori) {
            $kategori->status = $request->status; // Update status
            $kategori->save(); // Simpan perubahan
            return response()->json(['message' => 'Status berhasil diperbarui!']);
        }
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }
}