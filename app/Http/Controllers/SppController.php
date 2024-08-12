<?php

namespace App\Http\Controllers;

use App\Models\Spp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Exports\SppExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class SppController extends Controller
{
    public function index()
    {
        $spp = Spp::orderBy('tahun', 'desc')->paginate(10);
        return view('data-master.spp.index', compact('spp'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required|integer|min:1900|max:' . date('Y'),
            'nominal' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $nominal = str_replace('.', '', $request->input('nominal'));

        $spp = new Spp();
        $spp->tahun = $request->input('tahun');
        $spp->nominal = $nominal;
        $spp->save();

        return redirect()->route('spp.index')->with('status', 'Data SPP successfully added!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tahun' => 'required|integer',
            'nominal' => 'required|string',
        ]);

        $spp = Spp::findOrFail($id);
        $spp->update([
            'tahun' => $request->tahun,
            'nominal' => str_replace('.', '', $request->nominal), // Menghapus titik dari nominal sebelum menyimpan
        ]);

        return redirect()->route('spp.index')->with('status', 'Data SPP successfully updated!');
    }

    public function destroy($id)
    {
        try {
            // Temukan item berdasarkan ID dan hapus
            $spp = Spp::findOrFail($id);
            $spp->delete();

            // Set notifikasi sukses di sesi
            return redirect()->back()->with('status', 'Data SPP successfully deleted!');
        } catch (\Exception $e) {
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
