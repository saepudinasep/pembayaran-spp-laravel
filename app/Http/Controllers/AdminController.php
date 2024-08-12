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
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $admin = Admin::orderBy('nama', 'asc')->paginate(10);
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

        DB::beginTransaction();

        try {
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

            DB::commit();  // Commit the transaction if everything goes well

            return redirect()->route('admin.index')->with('status', 'Data Admin successfully added!');
        } catch (\Exception $e) {
            DB::rollBack();  // Rollback the transaction if any query fails

            return redirect()->back()->withErrors(['error' => 'An error occurred while saving data.'])->withInput();
        }
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

        // Mulai transaction
        DB::beginTransaction();

        try {
            $user->email = $request->email;
            $user->username = $request->username;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $admin->nama = $request->nama;
            $admin->save();

            // Commit transaction jika semua query berhasil
            DB::commit();

            return redirect()->route('admin.index')->with('status', 'Data Admin successfully updated!');
        } catch (\Exception $e) {
            // Rollback transaction jika terjadi error
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to update data.'])->withInput();
        }
    }

    public function destroy($id)
    {
        // Mulai transaction
        DB::beginTransaction();

        try {
            $admin = Admin::findOrFail($id);
            $user = $admin->user;

            // Hapus data admin terlebih dahulu
            $admin->delete();

            // Kemudian hapus data user yang terkait
            $user->delete();

            // Commit transaction jika semua operasi berhasil
            DB::commit();

            return redirect()->route('admin.index')->with('status', 'Data Admin successfully deleted!');
        } catch (\Exception $e) {
            // Rollback transaction jika terjadi error
            DB::rollBack();
            return redirect()->route('admin.index')->withErrors(['error' => 'Failed to delete data.']);
        }
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
