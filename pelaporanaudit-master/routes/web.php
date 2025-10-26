<?php

use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PelaporanAdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KategoriAdminController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\ProdukAdminController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// routing admin
Route::get('admin', [AdminController::class, 'index']);
Route::get('admin/create', [AdminController::class, 'create']);
Route::post('admin', [AdminController::class, 'store']);
Route::get('admin/{id}', [AdminController::class, 'show']);
Route::get('admin/{id}/edit', [AdminController::class, 'edit']);
Route::put('admin/{id}', [AdminController::class, 'update']);
Route::delete('admin/{id}', [AdminController::class, 'destroy']);

Route::get('pelaporan', [PelaporanAdminController::class, 'index']);
Route::get('pelaporan/create', [PelaporanAdminController::class, 'create']);
Route::post('pelaporan', [PelaporanAdminController::class, 'store']);
Route::get('pelaporan/{id}', [PelaporanAdminController::class, 'show']);
Route::get('pelaporan/{id}/edit', [PelaporanAdminController::class, 'edit']);
Route::put('pelaporan/{id}', [PelaporanAdminController::class, 'update']);
Route::delete('pelaporan/{id}', [PelaporanAdminController::class, 'destroy']);

Route::get('/', [UserController::class, 'index'])->name('home');
Route::get('allprodukuser', [UserController::class, 'allprodukuser'])->name('allprodukuser');
Route::get('homeseller', [UserController::class, 'index'])->name('homeseller');
Route::get('user', [UserController::class, 'index']);
Route::get('user/create', [UserController::class, 'create']);
Route::post('user', [UserController::class, 'store']);
Route::get('user/{id}', [UserController::class, 'show']);
Route::get('user/{id}/edit', [UserController::class, 'edit']);
Route::put('user/{id}', [UserController::class, 'update']);
Route::delete('user/{id}', [UserController::class, 'destroy']);
Route::get('produkseller', [UserController::class, 'produkseller'])->name('produk.seller.edit');

Route::get('/fetchproducts', [UserController::class, 'fetchProducts']);
Route::get('produkseller/{id}/edit', [UserController::class, 'editProdukseller']);
Route::put('produkseller/{id}', [UserController::class, 'updateProdukseller'])->name('produkseller.update');


Route::delete('hapusprodukseller/{id}', [UserController::class, 'hapusProdukseller'])->name('hapusprodukseller.destroy');

Route::get('product', [UserController::class, 'product'])->name('product');
Route::get('pesanansaya', [UserController::class, 'pesanansaya'])->name('pesanansaya');
Route::post('tambahprodukseller', [UserController::class, 'submitproduk'])->name('produk.submit');
Route::get('tambahprodukseller', [UserController::class, 'tambahprodukseller']);

Route::get('/report/excel', [TransactionController::class, 'downloadExcel'])->name('report.excel');
Route::get('/report/pdf', [TransactionController::class, 'downloadPdf'])->name('report.pdf');
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Route untuk Register
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/registeradmin', function () {
    return view('auth.registeradmin');
})->name('registeradmin');

Route::post('/registeradmin', [RegisterController::class, 'registeradmin'])->name('registeradmin');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Proses login
Route::post('/login', [AuthController::class, 'login'])->name('login.post');


// Rute untuk halaman home (dashboard)
// Route::get('/', [AuthController::class, 'index'])->name('home');

Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');


Route::post('/cart/add', [UserController::class, 'addToCart'])->name('cart.add');

Route::get('/product/{id}', [UserController::class, 'detail'])->name('product.detail');

Route::get('/filter-products', [UserController::class, 'filterProducts'])->name('filter.products');

Route::get('/keranjanguser', [UserController::class, 'keranjanguser'])->name('keranjanguser');

Route::delete('/cart/remove/{id}', [UserController::class, 'remove'])->name('cart.remove');
Route::post('/cart/checkout', [UserController::class, 'checkout'])->name('cart.checkout');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {
    Route::get('/profileuser', [UserController::class, 'profileuser'])->name('profileuser');
    Route::get('/keranjanguser', [UserController::class, 'keranjanguser'])->name('keranjanguser');
});

Route::post('/cart/add', [UserController::class, 'addToCart'])->name('cart.add');


Route::get('/profileuser', [UserController::class, 'profileuser'])->name('profileuser');
Route::get('/profileseller', [UserController::class, 'profileseller'])->name('profileseller');


Route::get('/transactions/filter', [TransactionController::class, 'filter'])->name('transactions.filter');
    
    // Update transaction status
Route::patch('/transactions/{transaction}/status', [TransactionController::class, 'updateStatus'])
        ->name('transactions.update.status');


Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

Route::get('/password/forgot', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/password/forgot', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/password/reset', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

Route::get('/transactions/chart-data', [TransactionController::class, 'getChartData'])->name('transactions.chart-data');


Route::post('/midtrans/callback', [UserController::class, 'handleCallback']);

Route::post('/upload-payment-proof', [UserController::class, 'uploadPaymentProof'])->name('upload.payment.proof');

Route::get('/transactions/detail/{id}', [TransactionController::class, 'show'])->name('transactions.detail');
Route::put('/transactions/{id}/status', [TransactionController::class, 'updateStatusPembayaran'])->name('transactions.updateStatusPembayaran');
Route::put('/transactions/{id}/statuspengiriman', [TransactionController::class, 'updateStatusPengiriman'])->name('transactions.updateStatusPengiriman');

Route::get('/transactions/{id}/detailadmin', [TransactionController::class, 'showall'])->name('transactions.detailadmin');

Route::post('/transactions/alert/{id}', [NotificationController::class, 'sendAlert'])->name('transactions.alert');
Route::post('/transactions/alertuser/{id}', [NotificationController::class, 'sendAlertToUser'])->name('transactions.sendAlertToUser');

Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications.get');
Route::post('/notifications/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
Route::get('/notifications/all', [NotificationController::class, 'getAll'])->name('notifications.all');

Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsReadUser'])->name('notifications.markAsReadUser');

Route::put('/transactions/upload-proof', [TransactionController::class, 'uploadProof'])->name('transactions.uploadProof');
Route::get('/download/invoice/{transaction}', [UserController::class, 'downloadInvoice'])->name('download.invoice');


Route::get('/transactions/{id}/download-pdfitem', [TransactionController::class, 'downloadPDFitem'])->name('transactions.downloadPDFitem');
Route::post('/verifikasi', [UserController::class, 'verifikasi'])->name('user.verifikasi');

Route::get('/verifikasiuser', [AdminController::class, 'verifyUser'])->name('admin.verifyUser');
Route::get('/admin/user/{id}', [AdminController::class, 'showuser'])->name('admin.user.show');
Route::post('/admin/user/{id}/update-status', [AdminController::class, 'updateStatususer'])->name('admin.user.update-status');

Route::post('/update-seller-status', [UserController::class, 'becomeSeller'])->name('user.becomeSeller');



Route::resource('kategori', KategoriController::class);



Route::get('/kategoriadmin', [KategoriAdminController::class, 'index'])->name('kategoriadmin.index');
Route::post('/kategoriadmin/update-status', [KategoriAdminController::class, 'updateStatus'])->name('kategoriadmin.updateStatus');

Route::get('/produkadmin', [ProdukAdminController::class, 'index'])->name('produkadmin.index');
Route::post('/produkadmin/update-status', [ProdukAdminController::class, 'updateStatus'])->name('produkadmin.updateStatus');


Route::get('service', [ServiceController::class, 'index'])->name('service.index'); // Menampilkan daftar pemesanan
Route::get('service/create', [ServiceController::class, 'create'])->name('service.create'); // Form tambah pemesanan
Route::post('service', [ServiceController::class, 'store'])->name('service.store'); // Simpan pemesanan baru
Route::get('service/{id}', [ServiceController::class, 'show'])->name('service.show'); // Tampilkan detail pemesanan
Route::get('service/{id}/edit', [ServiceController::class, 'edit'])->name('service.edit'); // Form edit pemesanan
Route::put('service/{id}', [ServiceController::class, 'update'])->name('service.update'); // Update data pemesanan
Route::delete('service/{id}', [ServiceController::class, 'destroy'])->name('service.destroy'); // Hapus pemesanan
Route::get('service/{id}/pdf', [ServiceController::class, 'cetakPdf'])->name('service.pdf');
Route::get('service/{id}/invoice', [ServiceController::class, 'generateInvoice'])->name('service.invoice');

Route::get('/productinfo/{productId}', [ItemController::class, 'productInfo'])->name('productinfo');
Route::get('/pdf/online', [UserController::class, 'downloadOnline'])->name('pdf.online');
Route::get('/pdf/offline', [UserController::class, 'downloadOffline'])->name('pdf.offline');

Route::get('/jadi-member', [UserController::class, 'showJadiMember'])->name('jadi.member');

// Route untuk menyimpan data member
Route::post('/store-member', [UserController::class, 'storeMember'])->name('store.member');

Route::put('/transactions/upload-proofr', [TransactionController::class, 'uploadProofR'])->name('transactions.uploadProofR');
Route::put('/transactions/upload-proofb', [TransactionController::class, 'uploadProofB'])->name('transactions.uploadProofB');

Route::post('/transaction/check', [TransactionController::class, 'check'])->name('transaction.check');

Route::post('/fines/submit', [FineController::class, 'submit'])->name('fines.submit');

Route::get('/banneduser', [AdminController::class, 'banneduser'])->name('admin.banneduser');
Route::get('/admin/userbanned/{id}', [AdminController::class, 'showuserbanned'])->name('admin.user.showuserbanned');
Route::put('/admin/userbanned/{id}/update-status', [AdminController::class, 'updateStatusBanned'])->name('admin.user.update-statusbanned');

Route::put('admin/user/update-statusbanned/{id}', [ProdukAdminController::class, 'updateStatusBanned'])
    ->name('admin.user.update-statusbanned');