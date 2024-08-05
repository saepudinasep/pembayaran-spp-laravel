<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        $userExists = User::exists();
        return view('auth.login', compact('userExists'));
    }

    public function login(Request $request)
    {
        // Validasi data yang diterima dari form login
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cek kredensial pengguna
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials, $request->remember)) {
            // Jika berhasil login, redirect ke halaman dashboard
            return redirect()->intended('/dashboard')->with('status', 'Login successful!');
        }

        // Jika gagal login, kembalikan ke halaman login dengan pesan error
        return redirect()->back()
            ->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ])
            ->withInput()
            ->with('error', 'Login failed. Please check your credentials and try again.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('status', 'Anda telah keluar.');
    }
}
