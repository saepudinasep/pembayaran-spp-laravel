<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function reset()
    {
        return view('auth.reset-password');
    }

    public function process(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'passwordOld' => 'required|string',
            'passwordNew' => 'required|string',
        ]);

        // dd($request);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->passwordOld, $user->password)) {
            return back()->withErrors([
                'username' => ['The provided credentials do not match our records.']
            ]);
        }

        $user->password = Hash::make($request->passwordNew);
        $user->save();

        return redirect()->route('login')->with('status', 'Password successfully reset.');
    }
}
