<?php

namespace App\Http\Controllers;

use App\Models\Spp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SppController extends Controller
{
    public function index()
    {
        $spp = Spp::all();
        return view('data-master.spp.index', compact('spp'));
    }

    public function add(Request $request)
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
}
