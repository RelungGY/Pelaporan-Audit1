<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Fine;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || Auth::user()->user_type !== 'admin') {
                return redirect()->route('login')->withErrors(['error' => 'You must be an admin to access this page.']);
            }

            return $next($request);
        });
    }

    public function index()
    {
        $admins = Admin::all();
        $transactions = Transaction::all();

        $data = [
            'content' => 'admin.index',
            'transactions' => $transactions,
        ];
        return view('admin.layouts.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Admin::create($request->all());
        return redirect()->route('admin.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        return view('admin.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        return view('admin.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        $admin->update($request->all());
        return redirect()->route('admin.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('admin.index');
    }

    /**
     * Verify the specified user.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function verifyUser(Admin $admin)
    {
        $user = User::all();

        $data = [
            'content' => 'admin.verifikasiuser',
            'user' => $user,
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function banneduser(Admin $admin)
    {
        $user = User::where('hukuman', 'banned')->get();

        $data = [
            'content' => 'admin.banneduser',
            'user' => $user,
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function showuserbanned($id)
{
    $user = User::findOrFail($id);
    $fines = Fine::where('user_id', $id)->get(); // Ambil denda terkait user

    return view('admin.layouts.index', [
        'data' => [
            'content' => 'admin.banneddetail',
            'user' => $user,
            'fines' => $fines,
        ],
    ]);
}

public function updateStatusBanned(Request $request, $id)
{
    // Cari Fine berdasarkan ID
    $fine = Fine::findOrFail($id);

    // Validasi input
    $validated = $request->validate([
        'status_verifikasi' => 'required|in:pending,approved,rejected',
    ]);

    // Update status denda
    $fine->update([
        'status' => $validated['status_verifikasi'],
    ]);

    \Log::info('Fine status updated', ['fine_id' => $id, 'status' => $fine->status]);

    // Update hukuman user jika status approved
    if ($validated['status_verifikasi'] === 'approved') {
        $fine->user->update([
            'hukuman' => 'clear', // Misalnya: hukuman dihapus
        ]);
    } elseif ($validated['status_verifikasi'] === 'rejected') {
        $fine->user->update([
            'hukuman' => 'banned', // Hukuman tetap
        ]);
    }

    \Log::info('User punishment updated', ['user_id' => $fine->user->id, 'hukuman' => $fine->user->hukuman]);

    return back()->with('success', 'Status denda dan hukuman pengguna berhasil diperbarui.');
}



    public function showuser($id)
    {
        $user = User::findOrFail($id);

        // dd($user);
        $data = [
            'content' => 'admin.userdetail',
            'user' => $user,
        ];

        return view('admin.layouts.index', ['data' => $data]);

    }

    public function updateStatususer(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|string'
        ]);

        $user = User::findOrFail($id);
        $user->status_verifikasi = $request->status_verifikasi;
        $user->save();

        return redirect()->route('admin.user.show', $id)->with('success', 'Status verifikasi berhasil diupdate.');
    }
}