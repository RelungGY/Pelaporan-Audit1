<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        // $kategori = Kategori::all();
        $kategori = Kategori::where('seller_id', auth()->id())->get();
        $data = [
            'content' => 'seller.kategori.kategori',
            'kategoris' => $kategori,
        ];
        return view('seller.layouts.index', ['data' => $data]);
    }

    public function create()
    {
        $kategori = Kategori::all();
        $data = [
            
            'content' => 'seller.kategori.create',
            'kategoris' => $kategori,
        ];
        return view('seller.layouts.index', ['data' => $data]);
        
    }

    public function store(Request $request)
{
    $request->validate([
        'namakategori' => 'required|string|max:255', // Validasi input
    ]);

    // Membuat data baru dengan status 'pending'
    Kategori::create([
        'seller_id' => auth()->id(), // Ambil ID user yang sedang login
        'namakategori' => $request->namakategori,
        'status' => 'pending', // Status default
    ]);

    // Redirect ke halaman index dengan pesan sukses
    return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
}


    public function show($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('kategori.show', compact('kategori'));
    }

    public function edit($id)
    {
        
        $kategori = Kategori::findOrFail($id);
        $data = [
            
            'content' => 'seller.kategori.edit',
            'kategoris' => $kategori,
        ];
        return view('seller.layouts.index', ['data' => $data]);
        
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'namakategori' => 'required|string|max:255', // Validasi input
    ]);

    $kategori = Kategori::findOrFail($id); // Cari data berdasarkan ID

    // Update hanya kolom namakategori
    $kategori->update([
        'namakategori' => $request->namakategori,
    ]);

    // Redirect ke halaman index dengan pesan sukses
    return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
}


    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}