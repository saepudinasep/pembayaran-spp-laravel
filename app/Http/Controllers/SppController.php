<?php

namespace App\Http\Controllers;

use App\Models\Spp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Exports\SppExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SppController extends Controller
{
    public function index()
    {
        $spp = Spp::orderBy('tahun', 'desc')->paginate(10);
        return view('data-master.spp.index', compact('spp'));
    }

    public function store(Request $request)
    {
        // Mulai transaction
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required|integer|min:1900|max:' . date('Y'),
                'nominal' => 'required|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Menghilangkan tanda titik dari nominal
            $nominal = str_replace('.', '', $request->input('nominal'));

            // Menyimpan data SPP
            $spp = new Spp();
            $spp->tahun = $request->input('tahun');
            $spp->nominal = $nominal;
            $spp->save();

            // Commit transaction jika semua operasi berhasil
            DB::commit();

            return redirect()->route('spp.index')->with('status', 'Data SPP successfully added!');
        } catch (\Exception $e) {
            // Rollback transaction jika terjadi error
            DB::rollBack();
            return redirect()->route('spp.index')->withErrors(['error' => 'Failed to add data.']);
        }
    }

    public function update(Request $request, $id)
    {
        // Mulai transaction
        DB::beginTransaction();

        try {
            $request->validate([
                'tahun' => 'required|integer',
                'nominal' => 'required|string',
            ]);

            // Temukan data SPP berdasarkan ID
            $spp = Spp::findOrFail($id);

            // Update data SPP dengan nominal yang sudah dihapus titiknya
            $spp->update([
                'tahun' => $request->tahun,
                'nominal' => str_replace('.', '', $request->nominal),
            ]);

            // Commit transaction jika semua operasi berhasil
            DB::commit();

            return redirect()->route('spp.index')->with('status', 'Data SPP successfully updated!');
        } catch (\Exception $e) {
            // Rollback transaction jika terjadi error
            DB::rollBack();
            return redirect()->route('spp.index')->withErrors(['error' => 'Failed to update data.']);
        }
    }

    public function destroy($id)
    {
        // Mulai transaction
        DB::beginTransaction();

        try {
            // Temukan item berdasarkan ID dan hapus
            $spp = Spp::findOrFail($id);
            $spp->delete();

            // Commit transaction jika penghapusan berhasil
            DB::commit();

            // Set notifikasi sukses di sesi
            return redirect()->back()->with('status', 'Data SPP successfully deleted!');
        } catch (\Exception $e) {
            // Rollback transaction jika terjadi kesalahan
            DB::rollBack();

            // Set notifikasi error di sesi jika terjadi kesalahan
            return redirect()->back()->with('error', 'Gagal menghapus data SPP.');
        }
    }

    public function export()
    {
        // Mengambil tanggal saat ini dengan format 'Y-m-d'
        $date = Carbon::now()->format('Y-m-d');

        // Nama file yang akan diekspor
        $fileName = 'Data-spp-' . $date . '.xlsx';

        return Excel::download(new SppExport, $fileName);
    }
}
