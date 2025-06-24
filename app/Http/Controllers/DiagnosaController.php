<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diagnosa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DiagnosaController extends Controller
{
    public function index()
    {
        $data = DB::table('master_diagnosa')
            ->orderBy('kode')
            ->get();

        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'nullable|integer',
            'kode' => 'required|string|max:20',
            'nama' => 'required|string',
        ]);

        DB::table('master_diagnosa')->updateOrInsert(
            ['id' => $request->id],
            [
                'kode' => $request->kode,
                'nama' => $request->nama,
                'updated_at' => now()
            ]
        );

        return response()->json(['message' => 'Diagnosa berhasil disimpan']);
    }
    public function destroy($id)
    {
        DB::table('master_diagnosa')->where('id', $id)->delete();
        return response()->json(['message' => 'Diagnosa berhasil dihapus']);
    }

    public function import(Request $request)
    {
        $data = $request->input('data'); // array of kode & nama

        if (!is_array($data)) {
            return response()->json(['message' => 'Format data tidak valid'], 400);
        }

        foreach ($data as $row) {
            if (!isset($row['kode']) || !isset($row['nama']))
                continue;

            DB::table('master_diagnosa')->updateOrInsert(
                ['kode' => $row['kode']],
                ['nama' => $row['nama'], 'updated_at' => now()]
            );
        }

        return response()->json(['message' => 'Import berhasil']);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:master_diagnosa,id',
            'kode' => 'required|string|max:20',
            'nama' => 'required|string|max:255',
        ]);

        $diagnosa = Diagnosa::find($request->id);

        $diagnosa->kode = $request->kode;
        $diagnosa->nama = $request->nama;
        $diagnosa->save();

        return response()->json([
            'message' => 'Data berhasil diperbarui',
            'data' => $diagnosa
        ]);
    }

}