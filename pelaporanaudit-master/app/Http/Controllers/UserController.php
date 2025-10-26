<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Member;
use App\Models\Transaction;
use Midtrans\Snap;
use App\Models\MidtransTransaction;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Kategori;
use App\Models\Service;
use PDF;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->check()) {
            $role = auth()->user()->user_type;
            $isSeller = auth()->user()->isseller;
        } else {
            $items = Item::where('stok', '>', 0)->where('available', 1)->orderBy('created_at', 'desc')->take(8)->get();
            $data = [
                'content' => 'user.index',
                'items' => $items
            ];
            return view('user.layouts.index', ['data' => $data]);
        }

        // Periksa apakah pengguna ingin melihat halaman seller atau user
        $viewAsSeller = $request->query('viewAsSeller', false);
        // dd($viewAsSeller);

        if ($role == 'user' && $isSeller == 'yes' && $viewAsSeller) {
            // Tampilkan halaman seller jika user ingin melihat sebagai seller
            // $transactions = Transaction::whereHas('item', function ($query) {
            //     $query->where('user_id', auth()->id());
            // })->where('user_id', auth()->id())->get();
            // $transactions = Transaction::whereIn('item_id', function ($query) {
            //     $query->select('id') // Pilih kolom `id` dari tabel item
            //           ->from('items') // Tabel item
            //           ->where('user_id', auth()->id()); // Filter user_id sesuai seller yang login
            // })->get();

            $transactions = Transaction::whereHas('item', function ($query) {
                $query->where('seller_id', auth()->id());
            })->get();
            
            // dd($transactions);
            $service = Service::where('seller_id', auth()->id())->get();
            // dd($transactions);
            $data = [
                'content' => 'seller.index',
                'transactions' => $transactions,
                'service' => $service
            ];
            return view('seller.layouts.index', ['data' => $data]);
        }

        

        // Tampilkan halaman user sebagai default
        // $items = Item::all();
        $items = Item::where('seller_id', '!=', auth()->id())
            ->where('stok', '>', 0)
            ->where('available', 1)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // dd($items);
        $data = [
            'content' => 'user.index',
            'items' => $items
        ];
        return view('user.layouts.index', ['data' => $data]);
    }

    public function downloadOnline()
    {
        $transactions = Transaction::whereHas('item', function ($query) {
            $query->where('seller_id', auth()->id());
        })->get();

        $pdf = PDF::loadView('pdf.transactions', ['transactions' => $transactions]);
        return $pdf->download('online-transactions.pdf');
    }

    public function downloadOffline()
    {
        $service = Service::where('seller_id', auth()->id())->get();

        $pdf = PDF::loadView('pdf.transactions_off', ['service' => $service]);
        return $pdf->download('offline-transactions.pdf');
    }


    public function product()
    {
        $data = [
            'content' => 'seller.product'
        ];
        return view('seller.layouts.index', ['data' => $data]);
    }

    public function allprodukuser()
    {
        $items = Item::where('seller_id', '!=', auth()->id())
            ->where('stok', '>', 0)
            ->where('available', 1)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

            $kategori = Kategori::where('status', 'disetujui')->get();
            
            
        $data = [
            'content' => 'user.allproduk',
            'items' => $items,
            'kategori' => $kategori
        ];
        return view('user.layouts.index', ['data' => $data]);
    }

    public function keranjanguser()
    {
        $userId = auth()->id();
        $transactions = Transaction::where('user_id', $userId)->where('status', 'unpaid')->get();
        $data = [
            'content' => 'user.keranjang',
            'transactions' => $transactions
        ];
        return view('user.layouts.index', ['data' => $data]);
    }


    public function remove($id)
    {
        $transaction = Transaction::findOrFail($id);
        $item = $transaction->item;
        $item->stok += $transaction->total_amount / $item->price_per_day;
        if ($item->stok > 0) {
            $item->available = 1;
        }
        $item->save();
        $transaction->delete();

        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }


    public function profileuser()
    {
        $id = auth()->user()->id;
        $transactions = Transaction::where('user_id', $id)->get();
        $notification = Notification::where('user_id', $id)->get();
        $notificationCount = $notification->where('is_read', false)->count();

        // dd($transactions);

        $data = [
            'content' => 'user.profile',
            'transactions' => $transactions,
            'notification' => $notification,
            'notificationCount' => $notificationCount,
        ];
        return view('user.layouts.index', ['data' => $data]);

    }

    public function profileseller()
    {
        $transactions = Transaction::all();
        $data = [
            'content' => 'seller.profile',
            'transactions' => $transactions
        ];
        return view('seller.layouts.index', ['data' => $data]);
    }


    public function filterProducts(Request $request)
    {
        $query = Item::where('seller_id', '!=', auth()->id())
            ->where('stok', '>', 0)
            ->where('available', 1)
            ->orderBy('created_at', 'desc')
            ->take(8);

        // Filter berdasarkan nama produk
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan kategori
        if ($request->has('categories') && is_array($request->categories)) {
            $query->whereIn('kategori_id', $request->categories);
        }

        // Filter berdasarkan harga minimum
        if ($request->has('min_price') && !empty($request->min_price)) {
            $query->where('price_per_day', '>=', $request->min_price);
        }

        // Urutkan data
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'terbaru':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'harga_tertinggi':
                    $query->orderBy('price_per_day', 'desc');
                    break;
                case 'harga_terendah':
                    $query->orderBy('price_per_day', 'asc');
                    break;
                default:
                    $query->orderBy('id', 'desc');
                    break;
            }
        }

        // Ambil data produk
        $products = $query->get();

        return response()->json($products);
    }

public function detail($id)
{
    $product = Item::findOrFail($id); // Ambil data produk berdasarkan ID
    $data = [
        'content' => 'user.produkdetail',
        'product' => $product
    ];
    return view('user.layouts.index', ['data' => $data]);
}



public function pesanansaya(Request $request)
{
    // Ambil status dari request, default 'Semua'
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
    $userId = auth()->id();
    if ($mappedStatus === null) {
        $transactions = Transaction::whereHas('item', function ($query) use ($userId) {
            $query->where('seller_id', $userId);
        })->paginate(10); // Semua data dengan paginasi
    } else {
        $transactions = Transaction::whereHas('item', function ($query) use ($userId) {
            $query->where('seller_id', $userId);
        })->where('status', $mappedStatus)->paginate(10); // Filter data berdasarkan status
    }

    $data = [
        'content' => 'seller.pesanan.pesanan',
        'transactions' => $transactions
    ];

    return view('seller.layouts.index', ['data' => $data, 'status' => $status]);
}

    


public function produkseller(Request $request)
{
    $items = Item::where('seller_id', auth()->id())->paginate(10);

    $data = [
        'content' => 'seller.produk.produk',
        'items' => $items,
    ];

    return view('seller.layouts.index', ['data' => $data]);
}




    public function tambahprodukseller()
{
    $kategori = Kategori::all(); // Ambil semua kategori
    $data = [
        'content' => 'seller.produk.tambah',
        'kategoris' => $kategori // Pastikan data kategori dikirimkan
    ];
    return view('seller.layouts.index', ['data' => $data]);
}


    public function submitProduk(Request $request)
{
    // Konversi 'available' ke boolean sebelum validasi
    $request->merge([
        'available' => $request->available === 'yes' ? true : false,
    ]);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price_per_day' => 'required|numeric|min:0',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'available' => 'required|boolean',
        'kategori_id' => 'required|exists:kategori,id', // Validasi kategori
        'stok' => 'required|integer|min:0',
    ]);

    // Inisialisasi array untuk gambar
    $imagePaths = [];

    try {
        // Simpan gambar jika ada
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public'); // Simpan gambar ke storage
                $imagePaths[] = $path; // Tambahkan path ke array
            }
        }

        // Simpan data ke tabel items
        $item = Item::create([
            'seller_id' => auth()->id(), // Ganti dengan ID seller dari session atau auth
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price_per_day' => $validated['price_per_day'],
            'available' => $validated['available'],
            'images' => json_encode($imagePaths), // Simpan path sebagai JSON
            'kategori_id' => $validated['kategori_id'], // Simpan kategori
            'stok' => $validated['stok'],
        ]);

        

        // Redirect dengan pesan sukses
        $data = [
            'content' => 'seller.produk.produk',
        ];
        return view('seller.layouts.index', ['data' => $data])->with('success', 'Produk berhasil ditambahkan.');
    } catch (\Exception $e) {
        // Log error dan redirect dengan pesan error
        \Log::error('Error saving produk: ' . $e->getMessage());
        return redirect()->back()->withErrors('Terjadi kesalahan saat menyimpan produk.');
    }
}



    public function editProdukseller($id)
    {
        $kategori = Kategori::all(); // Ambil semua kategori
        $item = Item::findOrFail($id);
        $data = [
            'content' => 'seller.produk.edit',
            'item' => $item,
            'kategoris' => $kategori
        ];
        return view('seller.layouts.index', ['data' => $data]);
    }

    public function updateProdukseller(Request $request, $id)
    {
        // Konversi 'available' ke boolean sebelum validasi
        $request->merge([
            'available' => $request->available === 'yes' ? true : false,
        ]);
    
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_day' => 'required|numeric|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'available' => 'required|boolean',
            'stok' => 'required|integer|min:0',
        ]);
    
        try {
            // Temukan item
            $item = Item::findOrFail($id);
    
            // Jika ada file gambar baru, hapus gambar lama dan simpan gambar baru
            if ($request->hasFile('images')) {
                // Hapus gambar lama dari storage
                if ($item->images) {
                    $existingImages = json_decode($item->images, true);
                    foreach ($existingImages as $oldImage) {
                        if (\Storage::disk('public')->exists($oldImage)) {
                            \Storage::disk('public')->delete($oldImage);
                        }
                    }
                }
    
                // Simpan gambar baru
                $newImagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('images', 'public');
                    $newImagePaths[] = $path;
                }
    
                // Update kolom images di database
                $item->images = json_encode($newImagePaths);
            }
    
            // Update data item
            $item->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price_per_day' => $validated['price_per_day'],
                'available' => $validated['available'],
                'images' => $item->images ?? $item->images, // Pertahankan gambar jika tidak ada gambar baru
                'stok' => $validated['stok'],
            ]);
    
            // Redirect dengan pesan sukses
           
            return redirect()->route('produk.seller.edit')->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            // Log error dan redirect dengan pesan error
            \Log::error('Error updating produk: ' . $e->getMessage());
            return redirect()->route('produk.seller.edit')->withErrors('Terjadi kesalahan saat memperbarui produk.');
        }
    }
    


    public function hapusProdukseller($id)
    {
        try {
            // Temukan item dan hapus
            $item = Item::findOrFail($id);
            $item->delete();

            // Redirect dengan pesan sukses
            $data = [
                'content' => 'seller.produk.produk',
            ];
            return view('seller.layouts.index', ['data' => $data])->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            // Log error dan redirect dengan pesan error
            \Log::error('Error deleting produk: ' . $e->getMessage());
            return redirect()->back()->withErrors('Terjadi kesalahan saat menghapus produk.');
        }
    }

    public function fetchProducts(Request $request)
    {
        \Log::debug('fetchProducts called', ['request' => $request->all()]);

        $filter = $request->get('filter', 'all');
        $query = $request->get('query', '');

        \Log::debug('Filter and query parameters', ['filter' => $filter, 'query' => $query]);

        $products = Item::query();
        // Filter berdasarkan seller_id dengan user yang login saat ini
        $userId = auth()->id();
        $products->where('seller_id', $userId);

        // Filter berdasarkan status
        if ($filter === 'nonaktif') {
            \Log::debug('Applying nonaktif filter');
            $products->where('available', 0);
        }

        // Filter berdasarkan pencarian (query)
        if (!empty($query)) {
            \Log::debug('Applying search query filter', ['query' => $query]);
            $products->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%");
            });
        }

        // Dapatkan semua data produk
        $products = $products->get();

        \Log::debug('Products fetched', ['products' => $products]);

        // Kembalikan tampilan tabel untuk Ajax
        return response()->json([
            'products' => $products
        ]);
    }


    public function addToCart(Request $request)
{
    \Log::debug('addToCart called', ['request' => $request->all()]);

    $validated = $request->validate([
        'item_id' => 'required|exists:items,id',
        'quantity' => 'required|integer|min:1',
        'total_price' => 'required|numeric|min:0',
    ]);

    \Log::debug('Validation passed', ['validated' => $validated]);

    try {
        // Ambil data item dari database untuk mengambil seller_id dan price
        $item = Item::findOrFail($validated['item_id']);
        $sellerId = $item->seller_id;

        // Kurangi stok item
        $item->stok -= $validated['quantity'];
        if ($item->stok <= 0) {
            $item->stok = 0;
            $item->available = 0;
        }
        $item->save();

        // Simpan data ke tabel `cart`
        $cart = Transaction::create([
            'item_id' => $validated['item_id'],
            'quantity' => $validated['quantity'],
            'seller_id' => $sellerId,
            'start_date' => now(),
            'end_date' => now()->addDays($validated['quantity']),
            'deadline' => now()->addDays($validated['quantity']),
            'total_amount' => $validated['total_price'],
            'status' => 'unpaid',
            'status_confirm' => 'unconfirmed',
            'user_id' => auth()->id(),
        ]);

        \Log::debug('Item added to cart', ['cart' => $cart]);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang!',
            'data' => $cart,
        ]);
    } catch (\Exception $e) {
        \Log::error('Error adding to cart', [
            'error' => $e->getMessage(),
            'stack' => $e->getTraceAsString(),
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat menambahkan produk ke keranjang.',
        ], 500);
    }
}



public function checkout(Request $request)
{
    \Log::debug('checkout called', ['request' => $request->all()]);
    $request->validate([
        'selected_items' => 'required|array',
        'selected_items.*' => 'exists:transactions,id', // Validasi bahwa ID transaksi valid
    ]);

    // Ambil semua transaksi berdasarkan ID yang dikirim
    $transactions = Transaction::whereIn('id', $request->selected_items)
        ->where('user_id', auth()->id()) // Pastikan hanya transaksi milik user yang diproses
        ->get();

    // Validasi: Pastikan transaksi ditemukan
    if ($transactions->isEmpty()) {
        \Log::warning('Invalid transactions or empty cart', ['selectedItemIds' => $selectedItemIds]);
        return response()->json(['success' => false, 'message' => 'Transaksi tidak valid atau keranjang kosong.'], 400);
    }

    // Hitung total amount
    $totalAmount = $transactions->sum('total_amount');
    \Log::debug('Total amount calculated', ['totalAmount' => $totalAmount]);

    // Buat order ID unik
    $orderId = 'ORDER-' . uniqid();
    \Log::debug('Generated order ID', ['orderId' => $orderId]);

    // // Konfigurasi Midtrans Snap
    // \Midtrans\Config::$serverKey = config('midtrans.server_key');
    // \Midtrans\Config::$isProduction = config('midtrans.is_production');
    // \Midtrans\Config::$isSanitized = true;
    // \Midtrans\Config::$is3ds = true;

    // Buat parameter untuk Midtrans Snap
    $params = [
        'transaction_details' => [
            'order_id' => $orderId,
            'gross_amount' => $totalAmount,
        ],
        'customer_details' => [
            'first_name' => auth()->user()->name,
            'email' => auth()->user()->email,
        ],
        'item_details' => $transactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'price' => $transaction->total_amount,
                'quantity' => 1,
                'name' => $transaction->item->name,
            ];
        })->toArray(),
    ];

    \Log::debug('Midtrans Snap parameters', ['params' => $params]);

    try {
        // Dapatkan Snap Token
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        \Log::debug('Snap token generated', ['snapToken' => $snapToken]);

        // Simpan status transaksi awal ke database (processing)
        foreach ($transactions as $transaction) {
            $transaction->update([
                'status' => 'processing',
                'order_id' => $orderId, // Simpan order ID untuk referensi
            ]);
        }

        return response()->json([
            'success' => true,
            'snap_token' => $snapToken,
            'order_id' => $orderId,
        ]);
    } catch (\Exception $e) {
        \Log::error('Error processing payment', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()], 500);
    }
}


public function handleCallback(Request $request)
{
    $payload = $request->all();
    \Log::debug('handleCallback payload', ['payload' => $payload]);

    $signatureKey = hash("sha512", $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . env('MIDTRANS_SERVER_KEY'));

    if ($signatureKey != $payload['signature_key']) {
        \Log::error('Invalid signature', ['expected' => $signatureKey, 'received' => $payload['signature_key']]);
        return response()->json(['message' => 'Invalid signature'], 403);
    }

    try {
        $transaction = MidtransTransaction::updateOrCreate(
            ['order_id' => $payload['order_id']],
            [
                'transaction_status' => $payload['transaction_status'],
                'payment_type' => $payload['payment_type'],
                'fraud_status' => $payload['fraud_status'] ?? null,
                'gross_amount' => $payload['gross_amount'],
                'user_id' => auth()->id(),
            ]
        );
        \Log::debug('MidtransTransaction updated or created', ['transaction' => $transaction]);
    } catch (\Exception $e) {
        \Log::error('Error updating or creating MidtransTransaction', ['error' => $e->getMessage(), 'payload' => $payload]);
        return response()->json(['message' => 'Error updating transaction'], 500);
    }

    // Update transaksi di database sesuai status
    if ($payload['transaction_status'] == 'settlement') {
        Transaction::where('status', 'processing')
            ->where('user_id', auth()->id())
            ->update(['status' => 'paid']);
    }

    return response()->json(['message' => 'Transaction updated']);
}
public function uploadPaymentProof(Request $request)
{
    \Log::debug('uploadPaymentProof called', ['request' => $request->all()]);

    $request->validate([
        'item_ids' => 'required|array', // Memastikan bahwa item_ids dikirim sebagai array
        'item_ids.*' => 'exists:items,id', // Setiap item_id harus valid
        'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Validasi file gambar
    ]);

    \Log::debug('Validation passed', ['validated' => $request->all()]);

    // Simpan file bukti pembayaran
    if (!$request->hasFile('bukti_pembayaran')) {
        \Log::error('File bukti pembayaran tidak ditemukan.');
        return response()->json([
            'success' => false,
            'message' => 'Bukti pembayaran wajib diunggah.',
        ]);
    }
    
    $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
    if (!$path) {
        \Log::error('Gagal menyimpan file bukti pembayaran.');
        return response()->json([
            'success' => false,
            'message' => 'Gagal menyimpan bukti pembayaran. Silakan coba lagi.',
        ]);
    }
        \Log::debug('Payment proof uploaded', ['path' => $path]);

    // Perbarui status transaksi untuk setiap item_id
    foreach ($request->item_ids as $item_id) {
        Transaction::where('item_id', $item_id)
            ->where('user_id', auth()->id()) // Memastikan hanya transaksi milik pengguna yang diperbarui
            ->update([
                'status_confirm' => 'pending',
                'bukti_pembayaran' => $path,
                'tanggal_pembayaran' => now(),
            ]);
        \Log::debug('Transaction updated', ['item_id' => $item_id, 'user_id' => auth()->id()]);
    }

    // Ambil transaksi berdasarkan item_ids dan user_id
    $transactions = Transaction::whereIn('item_id', $request->item_ids)
        ->where('user_id', auth()->id())
        ->get();

    return response()->json([
        'success' => true,
        'message' => 'Bukti pembayaran berhasil diunggah.',
        'download_url' => route('download.invoice', $transactions->first()->id),
    ]);
}

    public function downloadInvoice(Transaction $transaction)
    {
        // Validasi: Pastikan transaksi milik user
        if ($transaction->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke invoice ini.');
        }
    
        // Generate Invoice (contoh sederhana, ganti sesuai kebutuhan)
        $pdf = \PDF::loadView('invoice', ['transaction' => $transaction]);
    
        return $pdf->download('invoice-' . $transaction->order_id . '.pdf');
    }
    public function verifikasi(Request $request)
{
    $request->validate([
        'foto_wajah' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'foto_ttd' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Simpan file ke storage
    $fotoWajahPath = $request->file('foto_wajah')->store('verifikasi/foto_wajah', 'public');
    $fotoKtpPath = $request->file('foto_ktp')->store('verifikasi/foto_ktp', 'public');
    $fotoTtdPath = $request->file('foto_ttd')->store('verifikasi/foto_ttd', 'public');

    // Update data user
    $user = auth()->user();
    $user->foto_wajah = $fotoWajahPath;
    $user->foto_ktp = $fotoKtpPath;
    $user->foto_ttd = $fotoTtdPath;
    $user->status_verifikasi = 'pending';
    $user->save();

    return redirect()->back()->with('success', 'Data berhasil dikirim untuk verifikasi.');
}


public function becomeSeller(Request $request)
{
    $user = Auth::user(); // Ambil data pengguna yang login
    if (!$user) {
        return redirect()->back()->with('error', 'Anda harus login terlebih dahulu.');
    }

    $user->isseller = 'yes'; // Ubah status menjadi seller
    $user->save();
}



public function storeMember(Request $request)
{
    // Validasi input
    $request->validate([
        'nama_toko' => 'required|string|max:255',
        'alamat' => 'required|string',
        'no_telp' => 'required|string|max:15',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Ambil user ID dari user yang sedang login
    $user = Auth::user(); // Mengambil data pengguna yang sedang login
    if (!$user) {
        return redirect()->back()->with('error', 'Anda harus login terlebih dahulu.');
    }

    // Simpan data ke tabel member
    $member = new Member();
    $member->user_id = $user->id; // Pastikan user_id diambil dari pengguna login
    $member->nama_toko = $request->nama_toko;
    $member->alamat = $request->alamat;
    $member->no_telp = $request->no_telp;

    // Proses upload gambar jika ada
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $path = $file->store('uploads', 'public');
        $member->image = $path;
    }

    $member->save();

    // Panggil fungsi becomeSeller untuk mengubah status pengguna menjadi seller
    $this->becomeSeller($request);

    // Arahkan ke halaman seller index
    return redirect(url('/homeseller?viewAsSeller=true'))->with('success', 'Data member berhasil disimpan dan Anda sekarang adalah seller!');
}


public function showJadiMember()
{
    return view('seller.member.jadimember'); // Pastikan path sesuai dengan lokasi file
}


}