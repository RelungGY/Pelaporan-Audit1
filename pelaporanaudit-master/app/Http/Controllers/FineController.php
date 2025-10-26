<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FineController extends Controller
{
    public function submit(Request $request)
    {
        // Validasi file yang diunggah
        $request->validate([
            'payment_proof' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Simpan file ke storage
        $filePath = $request->file('payment_proof')->store('fines', 'public');

        // Simpan data ke tabel `fines`
        Fine::create([
            'user_id' => Auth::id(),
            'status' => 'pending',
            'payment_proof' => $filePath,
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu konfirmasi admin.');
    }
}