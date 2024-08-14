<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class SchoolClassController extends Controller
{
    public function index()
    {
        $schoolClass = SchoolClass::orderBy('jurusan', 'asc')->paginate(10);
        return view('data-master.schoolClass.index', compact('schoolClass'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'jurusan' => 'required|string|in:AK,AP,RPL,TITL,TKR,TSM'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $schoolClass = new SchoolClass();
            $schoolClass->nama_kelas = $request->nama;
            $schoolClass->jurusan = $request->jurusan;
            $schoolClass->save();

            DB::commit();

            return redirect()->route('kelas.index')->with('status', 'Data Kelas successfully added!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'An error occurred while saving data.'])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'jurusan' => 'required|string|in:AK,AP,RPL,TITL,TKR,TSM',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction();
        try {
            $schoolClass = SchoolClass::find($id);
            $schoolClass->nama_kelas = $request->nama;
            $schoolClass->jurusan = $request->jurusan;
            $schoolClass->save();
            DB::commit();
            return redirect()->route('kelas.index')->with('status', 'Data Kelas successfully updated!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'An error occurred while saving data.'])->withInput();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $schoolClass = SchoolClass::find($id);
            $schoolClass->delete();
            DB::commit();
            return redirect()->route('kelas.index')->with('status', 'Data Kelas successfully deleted!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'An error occurred while deleting data.']);
        }
    }

    public function export()
    {
        $date = Carbon::now()->format('Y-m-d');

        $fileName = 'Data-kelas-' . $date . '.xlsx';

        return Excel::download(new SchoolClass, $fileName);
    }
}
