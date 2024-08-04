<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }
    // Menangani pendaftaran pengguna
    protected function process(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = $this->create($request->all());
        $this->createAdmin($user, $request->all());
        return redirect()->route('login')->with('status', 'Account created successfully!');
    }

    // Validasi data pendaftaran
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|string|email|max:255|unique:users',
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
    }

    // Membuat pengguna baru
    protected function create(array $data)
    {
        return User::create([
            'id' => (string) Str::uuid(),
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'level' => 'admin',
        ]);
    }

    protected function createAdmin(User $user, array $data)
    {
        return Admin::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'nama' => $data['name'],
        ]);
    }
}
