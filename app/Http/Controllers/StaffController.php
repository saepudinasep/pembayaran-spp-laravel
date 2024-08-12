<?php

namespace App\Http\Controllers;

use App\Exports\StaffExport;
use App\Models\Staff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::orderBy('nama', 'asc')->paginate(10);
        return view('data-master.staff.index', compact('staff'));
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
            $user->level = 'petugas';
            $user->save();

            $staff = new Staff();
            $staff->id = (string) Str::uuid();
            $staff->user_id = $user->id;
            $staff->nama = $request->nama;
            $staff->save();

            DB::commit();  // Commit the transaction if everything goes well

            return redirect()->route('staff.index')->with('status', 'Data Staff successfully added!');
        } catch (\Exception $e) {
            DB::rollBack();  // Rollback the transaction if any query fails

            return redirect()->back()->withErrors(['error' => 'An error occurred while saving data.'])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);
        $user = $staff->user;

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

            $staff->nama = $request->nama;
            $staff->save();

            // Commit transaction jika semua query berhasil
            DB::commit();

            return redirect()->route('staff.index')->with('status', 'Data Staff successfully updated!');
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
            $staff = Staff::findOrFail($id);
            $user = $staff->user;

            // Hapus data staff terlebih dahulu
            $staff->delete();

            // Kemudian hapus data user yang terkait
            $user->delete();

            // Commit transaction jika semua operasi berhasil
            DB::commit();

            return redirect()->route('staff.index')->with('status', 'Data Staff successfully deleted!');
        } catch (\Exception $e) {
            // Rollback transaction jika terjadi error
            DB::rollBack();
            return redirect()->route('staff.index')->withErrors(['error' => 'Failed to delete data.']);
        }
    }

    public function export()
    {
        // Mengambil tanggal saat ini dengan format 'Y-m-d'
        $date = Carbon::now()->format('Y-m-d');

        // Nama file yang akan diekspor
        $fileName = 'Data-staff-' . $date . '.xlsx';

        return Excel::download(new StaffExport, $fileName);
    }
}
