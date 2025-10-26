<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    

    public function downloadExcel(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Ambil data transaksi berdasarkan rentang tanggal
        $transactions = Transaction::whereBetween('start_date', [$startDate, $endDate])->get();

        // dd($transactions, $startDate, $endDate);

        // Membuat Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'Item ID');
        $sheet->setCellValue('B1', 'User ID');
        $sheet->setCellValue('C1', 'Start Date');
        $sheet->setCellValue('D1', 'End Date');
        $sheet->setCellValue('E1', 'Deadline');
        $sheet->setCellValue('F1', 'Total Amount');
        $sheet->setCellValue('G1', 'Status');

        // Data transaksi
        $row = 2;
        foreach ($transactions as $transaction) {
            $sheet->setCellValue('A' . $row, $transaction->item_id);
            $sheet->setCellValue('B' . $row, $transaction->user_id);
            $sheet->setCellValue('C' . $row, $transaction->start_date);
            $sheet->setCellValue('D' . $row, $transaction->end_date);
            $sheet->setCellValue('E' . $row, $transaction->deadline);
            $sheet->setCellValue('F' . $row, $transaction->total_amount);
            $sheet->setCellValue('G' . $row, $transaction->status);
            $row++;
        }

        // Simpan file sementara
        $fileName = 'transactions_report_' . $startDate . '_to_' . $endDate . '.xlsx';
        $tempFilePath = storage_path($fileName);

        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        // Unduh file
        return response()->download($tempFilePath)->deleteFileAfterSend(true);
    }


    public function downloadPdf(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Ambil data transaksi berdasarkan rentang tanggal
        $transactions = Transaction::whereBetween('start_date', [$startDate, $endDate])->get();
        // dd($startDate, $endDate, $transactions);
        // Buat PDF
        $pdf = Pdf::loadView('reports.transactions_pdf', [
            'transactions' => $transactions,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);

        // Unduh file PDF
        $fileName = 'transactions_report_' . $startDate . '_to_' . $endDate . '.pdf';
        return $pdf->download($fileName);
    }

    public function filter(Request $request)
    {
        $query = Transaction::with('item')->latest();
        // Filter berdasarkan user yang login
        $query->where('user_id', auth()->id());
        // Handle status filtering
        if ($request->has('status')) {
            if (is_array($request->status)) {
                // Handle multiple statuses
                $query->whereIn('status', $request->status);
            } elseif ($request->status !== 'all') {
                // Handle single status
                $query->where('status', $request->status);
            }
        }

        // Handle search
        if ($request->search) {
            $query->whereHas('item', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $transactions = $query->get()->map(function ($transaction) {
            $images = json_decode($transaction->item->images, true);
            $firstImage = $images[0] ?? null;
            
            return [
                'id' => $transaction->id,
                'item_name' => $transaction->item->name,
                'item_image' => $firstImage ? asset('storage/' . $firstImage) : 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg',
                'start_date' => $transaction->start_date,
                'deadline' => $transaction->deadline,
                'status' => $transaction->status,
                'created_at' => $transaction->created_at,
                'updated_at' => $transaction->updated_at
            ];
        });

        return response()->json($transactions);
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $validStatuses = ['paid', 'unpaid', 'dikirim', 'dikembalikan', 'perlu dikirim'];
        
        $request->validate([
            'status' => ['required', 'string', Rule::in($validStatuses)]
        ]);

        $transaction->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status transaksi berhasil diperbarui',
            'transaction' => $transaction
        ]);
    }


    public function getChartData()
{
    $userId = auth()->id();
    $transactions = Transaction::where('user_id', $userId)->get();

    $monthlyEarnings = $transactions->groupBy(function ($transaction) {
        return \Carbon\Carbon::parse($transaction->created_at)->format('Y-m'); // Bulan dan Tahun
    })->map(function ($group) {
        return $group->sum('total_amount');
    });

    $statusCount = [
        'Perlu Dikirim' => $transactions->whereIn('status', ['perlu dikirim', 'dikirim'])->count(),
        'Selesai' => $transactions->where('status', 'selesai')->count(),
        'Pesanan Baru' => $transactions->where('status', 'baru')->count(),
    ];

    return response()->json([
        'monthlyEarnings' => $monthlyEarnings,
        'statusCount' => $statusCount,
    ]);
}

public function show($id)
{
    \Log::info('Transaction ID:', ['id' => $id]);

    $transaction = Transaction::with('item', 'user')
        ->where('id', $id)
        // ->where('user_id', auth()->id()) // Hanya transaksi milik pengguna
        ->firstOrFail();

        // dd($transaction);

    \Log::info('Transaction Found:', $transaction->toArray());

    $data = [
        'content' => 'seller.pesanan.detailpesanan',
        'transaction' => $transaction,
    ];

    return view('seller.layouts.index', ['data' => $data]);
}


public function showall($id)
{
    // Ambil transaksi berdasarkan ID
    $transaction = Transaction::with('item', 'user') // Eager load item dan user
        ->where('id', $id)
        ->firstOrFail();
        $data = [
            'content' => 'admin.detailpelaporan', 
            'transaction' => $transaction

        ];
        return view('admin.layouts.index', ['data' => $data]);
}


public function updateStatusPembayaran(Request $request, $id)
{
    // Validasi input
    $request->validate([
        'status_confirm' => 'required|in:pending,confirmed,rejected',
    ]);

    // Cari transaksi berdasarkan ID
    $transaction = Transaction::findOrFail($id);

    // Cek apakah user memiliki izin untuk mengubah (hanya seller/admin)
    if (auth()->user()->isseller !== 'yes') {
        return redirect()->back()->withErrors('Anda tidak memiliki izin untuk mengubah status transaksi ini.');
    }

    // Update status
    $transaction->update([
        'status' => 'perlu dikirim',
        'status_confirm' => $request->status_confirm,
    ]);

    // Redirect dengan pesan sukses
    return redirect()->route('transactions.detail', $id)
        ->with('success', 'Status transaksi berhasil diperbarui.');
}
public function updateStatusPengiriman(Request $request, $id)
{
    // Validasi input
    $request->validate([
        'status' => 'required|in:perlu dikirim,dikirim,Selesai Dikirim',
    ]);

    // Cari transaksi berdasarkan ID
    $transaction = Transaction::findOrFail($id);

    // Cek apakah user memiliki izin untuk mengubah (hanya seller/admin)
    if (auth()->user()->isseller !== 'yes') {
        return redirect()->back()->withErrors('Anda tidak memiliki izin untuk mengubah status transaksi ini.');
    }

    // Log debugging sebelum update
    \Log::debug('Updating transaction status', [
        'transaction_id' => $transaction->id,
        'old_status' => $transaction->status,
        'new_status' => $request->status,
        'user_id' => auth()->id(),
    ]);

    // Update status
    $transaction->update([
        'status' => $request->status,
    ]);

    // Log debugging setelah update
    \Log::debug('Transaction status updated successfully', [
        'transaction_id' => $transaction->id,
        'updated_status' => $transaction->status,
    ]);

    // Redirect dengan pesan sukses
    return redirect()->route('pesanansaya') // Pastikan route ini sesuai
        ->with('success', 'Status transaksi berhasil diperbarui.');
}
public function uploadProof(Request $request)
{
    $request->validate([
        'transaction_id' => 'required|exists:transactions,id',
        'bukti_pengembalian' => 'required|image|max:2048', // Maksimal 2MB
    ]);

    $transaction = Transaction::find($request->transaction_id);

    // Simpan gambar ke storage
    $path = $request->file('bukti_pengembalian')->store('bukti_pengembalian', 'public');

    // Perbarui data transaksi
    $transaction->update([
        'bukti_pengembalian' => $path,
        'status' => 'dikembalikan', // Status diperbarui sesuai
    ]);

    return redirect()->back()->with('success', 'Bukti pengembalian berhasil diupload.');
}
public function uploadProofR(Request $request)
{
    \Log::debug('Request received', ['data' => $request->all()]);

    $request->validate([
        'transaction_id' => 'required|exists:transactions,id',
        'bukti_pengembalian' => 'required|image|max:2048',
    ]);

    $transaction = Transaction::find($request->transaction_id);

    if (!$transaction) {
        \Log::error('Transaction not found', ['transaction_id' => $request->transaction_id]);
        return redirect()->back()->withErrors(['error' => 'Transaction not found.']);
    }

    \Log::debug('Transaction found', ['transaction_id' => $transaction->id]);

    $path = $request->file('bukti_pengembalian')->store('bukti_pengembalian', 'public');

    if (!$path) {
        \Log::error('File upload failed');
        return redirect()->back()->withErrors(['error' => 'File upload failed.']);
    }

    \Log::debug('File uploaded', ['path' => $path]);

    $transaction->update([
        'bukti_pengembalian' => $path,
        'status' => 'dikembalikan',
    ]);

    \Log::debug('Transaction updated', [
        'transaction_id' => $transaction->id,
        'status' => $transaction->status,
    ]);

    return redirect()->back()->with('success', 'Bukti pengembalian berhasil diupload.');
}

public function uploadProofB(Request $request)
{
    \Log::debug('Request received', ['data' => $request->all()]);

    $request->validate([
        'transaction_id' => 'required|exists:transactions,id',
        'bukti_pengembalian' => 'required|image|max:2048',
    ]);

    $transaction = Transaction::find($request->transaction_id);

    if (!$transaction) {
        \Log::error('Transaction not found', ['transaction_id' => $request->transaction_id]);
        return redirect()->back()->withErrors(['error' => 'Transaction not found.']);
    }

    \Log::debug('Transaction found', ['transaction_id' => $transaction->id]);

    $path = $request->file('bukti_pengembalian')->store('bukti_pengembalian', 'public');

    if (!$path) {
        \Log::error('File upload failed');
        return redirect()->back()->withErrors(['error' => 'File upload failed.']);
    }

    \Log::debug('File uploaded', ['path' => $path]);

    $transaction->update([
        'bukti_pengembalian' => $path,
        'status' => 'dikembalikan',
    ]);

    \Log::debug('Transaction updated', [
        'transaction_id' => $transaction->id,
        'status' => $transaction->status,
    ]);

    return redirect()->back()->with('success', 'Bukti pengembalian berhasil diupload.');
}

public function downloadPDFitem($id)
{
    // Cari transaksi berdasarkan ID
    $transaction = Transaction::with('item')->findOrFail($id);

    // Render tampilan detail transaksi menjadi PDF
    $pdf = PDF::loadView('transactions.pdf', compact('transaction'));

    // Unduh file PDF dengan nama yang sesuai
    return $pdf->download('detail_transaksi_' . $transaction->id . '.pdf');
}

public function check(Request $request)
{
    $userId = auth()->id();
    $itemId = $request->item_id;

    // Cek transaksi apakah barang ini sedang disewa oleh user
    $exists = DB::table('transactions')
        ->where('user_id', $userId)
        ->where('item_id', $itemId)
        ->where('status', '!=', 'dikembalikan') // Barang belum dikembalikan
        ->exists();

    if ($exists) {
        return response()->json([
            'exists' => true,
            'message' => 'Barang ini masih dalam status sewa.'
        ], 200);
    }

    return response()->json(['exists' => false], 200);
}


}