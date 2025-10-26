<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $data = [
            'content' => 'user.editprofile',
            'user' => $user
        ];
        return view('user.layouts.index', ['data' => $data]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'alamat' => ['required', 'string'],
            'images' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'foto_ktp' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Validasi foto KTP
            'foto_wajah' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Validasi foto wajah user
            'foto_ttd' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Validasi foto TTD user

        ];

        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = ['min:8', 'confirmed'];
        }

        $validated = $request->validate($rules);

        // Handle image upload
        if ($request->hasFile('images')) {
            // Delete old image if exists
            if ($user->images) {
                Storage::disk('public')->delete($user->images);
            }
            
            $imagePath = $request->file('images')->store('profile-images', 'public');
            $user->images = $imagePath;
        }

        // Handle foto KTP upload
        if ($request->hasFile('foto_ktp')) {
            // Delete old foto KTP if exists
            if ($user->foto_ktp) {
                Storage::disk('public')->delete($user->foto_ktp);
            }
        
            $fotoKtpPath = $request->file('foto_ktp')->store('verifikasi/foto_ktp', 'public');
            $user->foto_ktp = $fotoKtpPath; // Simpan path ke model
        }
        
        // Handle foto wajah upload
        if ($request->hasFile('foto_wajah')) {
            // Delete old foto wajah if exists
            if ($user->foto_wajah) {
                Storage::disk('public')->delete($user->foto_wajah);
            }
    
            $fotoWajahPath = $request->file('foto_wajah')->store('verifikasi/foto_wajah', 'public');
            $user->foto_wajah = $fotoWajahPath; // Simpan path ke model
        }

        // Handle foto TTD upload
        if ($request->hasFile('foto_ttd')) {
            // Delete old foto TTD if exists
            if ($user->foto_ttd) {
                Storage::disk('public')->delete($user->foto_ttd);
            }
    
            $fotoTtdPath = $request->file('foto_ttd')->store('verifikasi/foto_ttd', 'public');
            $user->foto_ttd = $fotoTtdPath; // Simpan path ke model
        }
        
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->alamat = $validated['alamat'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->status_verifikasi = 'pending';

        $user->save();

        return redirect()->back()->with('success', 'Profile berhasil diperbarui');
    }
}