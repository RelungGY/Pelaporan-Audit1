<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class PelaporanAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'Semua');

        // Mapping status filter ke status database
        $statusMapping = [
            'Semua' => null,
            'Belum Dibayar' => 'unpaid',
            'Perlu Dikirim' => 'Perlu Dikirim',
            'Dikirim' => 'Dikirim',
            'Selesai Dikirim' => 'Selesai Dikirim',
            'Status Pengembalian' => 'dikembalikan',
        ];
    
        // Cek apakah status ada dalam mapping
        $mappedStatus = $statusMapping[$status] ?? null;
    
        // Ambil data transaksi dengan paginasi dan cek user_id
        if ($mappedStatus === null) {
            $transactions = Transaction::paginate(10); // Semua data dengan paginasi
        } else {
            $transactions = Transaction::where('status', $mappedStatus)->paginate(5); // Filter data berdasarkan status
        }
    
        $data = [
            'content' => 'admin.pelaporan',
            'transactions' => $transactions,
        ];
        return view('admin.layouts.index', ['data' => $data]);
    }
}