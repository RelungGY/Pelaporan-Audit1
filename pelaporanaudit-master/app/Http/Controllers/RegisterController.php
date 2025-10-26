<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
{
    // Validasi input
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|string|max:255',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // Simpan ke database
    $user = User::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'phone' => $validatedData['phone'],
        'password' => Hash::make($validatedData['password']),
        'user_type' => 'user', // Set nilai default untuk user_type
    ]);

    // Redirect atau response
    return redirect()->route('login')->with('success', 'Registration successful!');
}

public function registeradmin(Request $request)
{
    // Validasi input
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|string|max:255',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // Simpan ke database
    $user = User::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'phone' => $validatedData['phone'],
        'password' => Hash::make($validatedData['password']),
        'user_type' => 'admin', // Set nilai default untuk user_type
    ]);

    // Redirect atau response
    return redirect()->route('login')->with('success', 'Registration successful!');
}


}