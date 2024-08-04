<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function reset()
    {
        return view('auth.reset-password');
    }

    public function process(Request $request)
    {
    }
}
