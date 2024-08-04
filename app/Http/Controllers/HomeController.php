<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            // Jika pengguna sudah terautentikasi, arahkan ke dashboard
            return redirect()->route('dashboard.index');
        }

        // Jika pengguna belum terautentikasi, arahkan ke halaman login
        return redirect()->route('login');
    }
}
