<?php

namespace App\Http\Controllers;

use App\Exports\AdminExport;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $admin = Admin::orderBy('nama', 'desc')->paginate(10);
        return view('data-master.admin.index', compact('admin'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = new User();
        $user->id = (string) Str::uuid();
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = Hash::make($request->username);
        $user->level = 'admin';
        $user->save();

        $admin = new Admin();
        $admin->id = (string) Str::uuid();
        $admin->user_id = $user->id;
        $admin->nama = $request->nama;
        $admin->save();

        return redirect()->route('admin.index')->with('status', 'Data Admin successfully added!');
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $user = $admin->user;

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->email = $request->email;
        $user->username = $request->username;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $admin->nama = $request->nama;
        $admin->save();

        return redirect()->route('admin.index')->with('status', 'Data Admin successfully updated!');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $user = $admin->user;

        $admin->delete();
        $user->delete();

        return redirect()->route('admin.index')->with('status', 'Data Admin successfully deleted!');
    }

    public function export()
    {
        // Mengambil tanggal saat ini dengan format 'Y-m-d'
        $date = Carbon::now()->format('Y-m-d');

        // Nama file yang akan diekspor
        $fileName = 'Data-admin-' . $date . '.xlsx';
        return Excel::download(new AdminExport, $fileName);
    }
}
