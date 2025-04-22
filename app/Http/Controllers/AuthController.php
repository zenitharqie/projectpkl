<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('admin.login');
    }

    // Memproses login
   // AuthController.php
   public function login(Request $request)
{
    // Validasi input
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Cek kredensial di database
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        // Jika berhasil, redirect ke dashboard
        return redirect()->intended('/admin/dashboard');
    }

    // Jika gagal, kembalikan dengan error
    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ]);
}
   

    // Logout
    public function logout(Request $request)
    {
        Auth::logout(); // ini WAJIB
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/admin/login');
    }
    

}