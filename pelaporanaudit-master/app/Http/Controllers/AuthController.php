<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login'); // Pastikan file login.blade.php ada di resources/views/auth
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Coba login menggunakan Auth::attempt()
        if (Auth::attempt($credentials)) {
            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            // Cek user_type (contoh: admin, user biasa)
            if (Auth::user()->user_type == 'admin') {
                // Redirect ke halaman admin
                return redirect('/admin')->with('success', 'Login berhasil sebagai admin!');
            }

            // Redirect ke halaman home (index)
            return redirect('/')->with('success', 'Login berhasil!');
        }

        // Jika login gagal, kembalikan ke form login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Fungsi logout
    public function logout(Request $request)
    {
        // Logout user
        Auth::logout();

        // Redirect to login page
        return redirect('/login')->with('success', 'Anda telah logout!');
    }


    // Tampilkan halaman home (index)
    public function index()
    {
        return view('user.index'); 
    }
}